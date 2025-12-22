<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\Pgsql;

use AqHub\Core\Infrastructure\Database\PgsqlConnection;
use AqHub\Core\Result;
use AqHub\Items\Domain\Entities\Armor;
use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Domain\Repositories\Data\ArmorData;
use AqHub\Items\Domain\Repositories\Filters\ArmorFilter;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Shared\Domain\Abstractions\Filter;
use AqHub\Shared\Domain\Contracts\Identifier;
use AqHub\Shared\Domain\Enums\ItemTag;
use AqHub\Shared\Domain\ValueObjects\StringIdentifier;
use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\QueryFactory;
use DateTime;
use PDO;
use PDOException;

class PgsqlArmorRepository implements ArmorRepository
{
    public function __construct(
        private readonly PgsqlConnection $db,
        private readonly QueryFactory $query
    ) {
    }

    public function hydrate(array $data): ArmorData
    {
        $name         = Name::create($data['name'])->unwrap();
        $description  = Description::create($data['description'])->unwrap();
        $identifier   = StringIdentifier::create($data['hash'])->unwrap();
        $tags         = new ItemTags(array_map(fn (string $tag) => ItemTag::fromString($tag)->unwrap(), $data['tags']));
        $registeredAt = new DateTime($data['registered_at']);

        $rarity = ItemRarity::fromString($data['rarity'] ?? '');
        $rarity = $rarity->isError() ? null : $rarity->unwrap();

        return new ArmorData(
            $identifier,
            $name,
            $description,
            $tags,
            $registeredAt,
            $rarity
        );
    }

    /**
     * @param StringIdentifier $identifier
     */
    public function findByIdentifier(Identifier $identifier)
    {
        $select = $this->query->newSelect();

        $select->from('armors')
            ->cols(['*'])
            ->where('hash = :hash', ['hash' => $identifier->getValue()]);

        $statement = $this->db->connection->prepare($select->getStatement());
        $statement->execute($select->getBindValues());
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        $tags           = $this->findAllTags([$result['id']]);
        $result['tags'] = key_exists($result['id'], $tags) ? $tags[$result['id']] : [];

        return $this->hydrate($result);
    }

    /**
     * @param ArmorFilter $filter
     * @return ArmorData[]
     */
    public function findAll(Filter $filter): array
    {
        $select = $this->query->newSelect();

        $select
            ->from('armors as a')
            ->cols(['a.*']);

        $select = $this->buildWhere($filter, $select);

        $select->orderBy(['id ASC']);

        $statement = $this->db->connection->prepare($select->getStatement());
        $statement->execute($select->getBindValues());
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (empty($result)) {
            return $result;
        }

        $identifiers = array_column($result, 'id');
        $tags        = $this->findAllTags($identifiers);

        $armors = array_map(
            function ($data) use ($tags) {
                $data['tags'] = isset($tags[$data['id']]) ? $tags[$data['id']] : [];
                return $data;
            },
            $result
        );

        return array_map([$this, 'hydrate'], $armors);
    }

    private function findAllTags(array $identifiers)
    {
        $select = $this->query->newSelect();

        $select
            ->from('armor_tags')
            ->cols(['armor_id', 'tag']);

        $select->where('armor_id IN (:armor_ids)', ['armor_ids' => $identifiers]);

        $statement = $this->db->connection->prepare($select->getStatement());
        $statement->execute($select->getBindValues());
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $tags[(int) $row['armor_id']][] = $row['tag'];
        }

        return $tags ?? [];
    }

    public function save(ItemInfo $info): Result
    {
        $identifier = ItemIdentifierGenerator::generate($info, Armor::class)->unwrap();
        if ($this->findByIdentifier($identifier)) {
            return Result::error('An item with same identifier already exists.', null);
        }

        $insert = $this->query->newInsert();

        $insert
            ->into('armors')
            ->cols([
                'hash' => $identifier->getValue(),
                'name' => $info->getName(),
                'description' => $info->getDescription(),
                'rarity' => $info->getRarity()->toString()
            ]);

        try {
            $this->db->connection->beginTransaction();
            $statement = $this->db->connection->prepare($insert->getStatement());

            $executed = $statement->execute($insert->getBindValues());
            if (!$executed) {
                return Result::error('Failed to insert armor data.', null);
            }

            $armorId = $this->db->connection->lastInsertId('armors_id_seq');

            $insert = $this->query->newInsert();
            $insert->into('armor_tags');

            foreach ($info->tags as $tag) {
                $insert->addRow([
                    'tag' => $tag->toString(),
                    'armor_id' => $armorId
                ]);
            }

            $statement = $this->db->connection->prepare($insert->getStatement());
            $statement->execute($insert->getBindValues());

            $this->db->connection->commit();
            return Result::success('Armor saved successfully.', null);
        } catch (PDOException $e) {
            $this->db->connection->rollBack();
            return Result::error('Database error: ' . $e->getMessage(), null);
        }
    }

    /**
     * @param ArmorFilter $filter
     * @return int
     */
    public function countAll(Filter $filter): int
    {
        $select = $this->query->newSelect();

        $select
            ->from('armors as a')
            ->cols(['a.*']);

        $select = $this->buildWhere($filter, $select, ignorePagination: true);


        $statement = $this->db->connection->prepare($select->getStatement());
        $statement->execute($select->getBindValues());
        $total = $statement->rowCount();

        return $total;
    }

    private function buildWhere(ArmorFilter $filter, SelectInterface $select, bool $ignorePagination = false): SelectInterface
    {
        if (count($filter->rarities) > 0) {
            $select->where('rarity IN (:rarities)', ['rarities' => array_map(fn ($rarity) => $rarity->toString(), $filter->rarities)]);
        }

        if (count($filter->tags) > 0) {
            $select->join('INNER', 'armor_tags as at', 'a.id = at.armor_id');
            $select->where('at.tag IN (:tags)', ['tags' => array_map(fn ($tag) => $tag->toString(), $filter->tags)]);
            $select->distinct();
        }

        if (isset($filter->name) && !is_null($filter->name)) {
            $select->where('a.name ILIKE :name', ['name' => '%' . $filter->name->value . '%']);
        }

        if (!$ignorePagination) {
            $limit  = $filter->pageSize;
            $offset = ($filter->page - 1) * $filter->pageSize;

            $select->limit($limit)->offset($offset);
        }

        return $select;
    }
}
