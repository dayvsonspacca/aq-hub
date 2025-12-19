<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\Pgsql;

use AqHub\Core\Infrastructure\Database\PgsqlConnection;
use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\Repositories\CapeRepository;
use AqHub\Items\Domain\Repositories\Data\CapeData;
use AqHub\Items\Domain\Repositories\Filters\CapeFilter;
use AqHub\Items\Domain\ValueObjects\Description;
use AqHub\Items\Domain\ValueObjects\ItemTags;
use AqHub\Items\Domain\ValueObjects\Name;
use AqHub\Shared\Domain\Abstractions\Filter;
use AqHub\Shared\Domain\Contracts\Identifier;
use AqHub\Shared\Domain\Enums\ItemTag;
use AqHub\Shared\Domain\ValueObjects\StringIdentifier;
use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\QueryFactory;
use DateTime;
use PDO;

class PgsqlCapeRepository implements CapeRepository
{
    public function __construct(
        private readonly PgsqlConnection $db,
        private readonly QueryFactory $query
    ) {}

    public function hydrate(array $data): CapeData
    {
        $name         = Name::create($data['name'])->unwrap();
        $description  = Description::create($data['description'])->unwrap();
        $identifier   = StringIdentifier::create($data['hash'])->unwrap();
        $tags         = new ItemTags(array_map(fn(string $tag) => ItemTag::fromString($tag)->unwrap(), $data['tags']));
        $registeredAt = new DateTime($data['registered_at']);
        $canAccessBank = (bool) $data['can_access_bank'];

        $rarity = ItemRarity::fromString($data['rarity'] ?? '');
        $rarity = $rarity->isError() ? null : $rarity->unwrap();

        return new CapeData(
            $identifier,
            $name,
            $description,
            $tags,
            $registeredAt,
            $rarity,
            $canAccessBank
        );
    }

    /**
     * @param StringIdentifier $identifier
     */
    public function findByIdentifier(Identifier $identifier)
    {
        $select = $this->query->newSelect();

        $select->from('capes')
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
     * @param CapeFilter $filter
     * @return CapeData[]
     */
    public function findAll(Filter $filter): array
    {
        $select = $this->query->newSelect();

        $select
            ->from('capes as c')
            ->cols(['c.*']);

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

        $capes = array_map(
            function ($data) use ($tags) {
                $data['tags'] = isset($tags[$data['id']]) ? $tags[$data['id']] : [];
                return $data;
            },
            $result
        );

        return array_map([$this, 'hydrate'], $capes);
    }

    private function findAllTags(array $identifiers)
    {
        $select = $this->query->newSelect();

        $select
            ->from('cape_tags')
            ->cols(['cape_id', 'tag']);

        $select->where('cape_id IN (:cape_ids)', ['cape_ids' => $identifiers]);

        $statement = $this->db->connection->prepare($select->getStatement());
        $statement->execute($select->getBindValues());
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $tags[(int) $row['cape_id']][] = $row['tag'];
        }

        return $tags ?? [];
    }

    /**
     * @param CapeFilter $filter
     * @return int
     */
    public function countAll(Filter $filter): int
    {
        $select = $this->query->newSelect();

        $select
            ->from('capes as c')
            ->cols(['c.*']);

        $select = $this->buildWhere($filter, $select, ignorePagination: true);


        $statement = $this->db->connection->prepare($select->getStatement());
        $statement->execute($select->getBindValues());
        $total = $statement->rowCount();

        return $total;
    }

    private function buildWhere(CapeFilter $filter, SelectInterface $select, bool $ignorePagination = false): SelectInterface
    {
        if (count($filter->rarities) > 0) {
            $select->where('rarity IN (:rarities)', ['rarities' => array_map(fn($rarity) => $rarity->toString(), $filter->rarities)]);
        }

        if (count($filter->tags) > 0) {
            $select->join('INNER', 'cape_tags as ct', 'c.id = ct.cape_id');
            $select->where('ct.tag IN (:tags)', ['tags' => array_map(fn($tag) => $tag->toString(), $filter->tags)]);
            $select->distinct();
        }

        if (isset($filter->name) && !is_null($filter->name)) {
            $select->where('c.name ILIKE :name', ['name' => '%' . $filter->name->value . '%']);
        }

        if (isset($filter->canAccessBank) && !is_null($filter->canAccessBank))  {
            $select->where('c.can_access_bank = :can_access_bank', ['can_access_bank' => $filter->canAccessBank ? 1 : 0]);
        }

        if (!$ignorePagination) {
            $limit  = $filter->pageSize;
            $offset = ($filter->page - 1) * $filter->pageSize;

            $select->limit($limit)->offset($offset);
        }

        return $select;
    }
}
