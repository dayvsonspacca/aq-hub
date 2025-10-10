<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\Sql;

use AqHub\Items\Domain\Entities\Cape;
use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\Repositories\CapeRepository;
use AqHub\Items\Domain\Repositories\Data\CapeData;
use AqHub\Items\Domain\Repositories\Filters\CapeFilter;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Shared\Domain\ValueObjects\{Result, StringIdentifier};
use AqHub\Shared\Infrastructure\Database\Connection;
use DateTime;
use DomainException;

class SqlCapeRepository implements CapeRepository
{
    public function __construct(private readonly Connection $db)
    {
    }

    /**
     * @return Result<CapeData|null>
     */
    public function findByIdentifier(StringIdentifier $identifier): Result
    {
        $select = $this->db->builder->newSelect()
            ->from('capes')
            ->cols(['*'])
            ->where('hash = :hash')
            ->bindValue('hash', $identifier->getValue());

        $capeData = $this->db->fetchOne($select->getStatement(), ['hash' => $identifier->getValue()]);

        if (!$capeData) {
            return Result::error(null, null);
        }

        $name          = Name::create($capeData['name'])->unwrap();
        $description   = Description::create($capeData['description'])->unwrap();
        $canAccessBank = (bool) $capeData['can_access_bank'];

        $select = $this->db->builder->newSelect()
            ->from('cape_tags')
            ->cols(['tag'])
            ->where('cape_id = :cape_id')
            ->bindValue('cape_id', $capeData['id']);

        $tagsData  = $this->db->fetchAll($select->getStatement(), ['cape_id' => $capeData['id']]);
        $tags      = new ItemTags(array_map(fn ($row) => TagType::fromString($row['tag'])->unwrap(), $tagsData));

        $rarity = ItemRarity::fromString($capeData['rarity'] ?? '');
        $rarity = $rarity->isError() ? null : $rarity->getData();

        return Result::success(
            null,
            new CapeData(
                $identifier,
                $name,
                $description,
                $tags,
                $canAccessBank,
                new DateTime($capeData['registered_at']),
                $rarity
            )
        );
    }

    /**
     * @return Result<array<CapeData>>
     */
    public function findAll(CapeFilter $filter): Result
    {
        $select = $this->db->builder->newSelect()
            ->from('capes as c')
            ->cols(['c.*']);

        if (count($filter->rarities) > 0) {
            $select->where('rarity IN (:rarities)', ['rarities' => array_map(fn ($rarity) => $rarity->toString(), $filter->rarities)]);
        }

        if (count($filter->tags) > 0) {
            $select->join('INNER', 'cape_tags as ct', 'c.id = ct.armor_id');
            $select->where('ct.tag IN (:tags)', ['tags' => array_map(fn ($tag) => $tag->toString(), $filter->tags)]);
            $select->distinct();
        }

        if (isset($filter->name) && !is_null($filter->name)) {
            $select->where('c.name ILIKE :name', ['name' => '%' . $filter->name->value . '%']);
        }

        $limit  = $filter->pageSize;
        $offset = ($filter->page - 1) * $filter->pageSize;

        $select->limit($limit)->offset($offset)->orderBy(['id ASC']);

        $capesData = $this->db->fetchAll($select->getStatement(), $select->getBindValues());

        if (!$capesData) {
            return Result::success(null, []);
        }

        $armors = [];

        foreach ($capesData as $capeData) {
            $armorId = (int)$capeData['id'];

            $tagsSelect = $this->db->builder->newSelect()
                ->from('cape_tags')
                ->cols(['tag'])
                ->where('cape_id = :cape_id_' . $armorId)
                ->bindValue('cape_id_' . $armorId, $armorId);

            $tagsData = $this->db->fetchAll($tagsSelect->getStatement(), ['cape_id_' . $armorId => $armorId]);
            $tags     = new ItemTags(array_map(fn ($row) => TagType::fromString($row['tag'])->unwrap(), $tagsData));

            $rarity = ItemRarity::fromString($capeData['rarity'] ?? '');
            $rarity = $rarity->isError() ? null : $rarity->getData();

            $name          = Name::create($capeData['name'])->unwrap();
            $description   = Description::create($capeData['description'])->unwrap();
            $canAccessBank = (bool) $capeData['can_access_bank'];

            $armors[] = new CapeData(
                StringIdentifier::create($capeData['hash'])->unwrap(),
                $name,
                $description,
                $tags,
                $canAccessBank,
                new DateTime($capeData['registered_at']),
                $rarity
            );
        }

        return Result::success(null, $armors);
    }

    /**
     * @return Result<CapeData|null>
     */
    public function persist(ItemInfo $itemInfo, bool $canAccessBank): Result
    {
        try {
            $this->db->getConnection()->beginTransaction();

            $hash = ItemIdentifierGenerator::generate($itemInfo, Cape::class);
            if ($hash->isError()) {
                throw new DomainException('Failed to generate StringIdentifier: ' . $hash->getMessage());
            }

            $hash = $hash->getData();

            if ($this->findByIdentifier($hash)->isSuccess()) {
                throw new DomainException('A Cape with same identifier already exists: ' . $hash->getValue());
            }

            $registeredAt = new DateTime();

            $insert = $this->db->builder->newInsert()
                ->into('capes')
                ->cols([
                    'name' => $itemInfo->getName(),
                    'hash' => $hash->getValue(),
                    'description' => $itemInfo->getDescription(),
                    'can_access_bank' => $canAccessBank ? 'TRUE' : 'FALSE',
                    'rarity' => $itemInfo->getRarity() ? $itemInfo->getRarity()->toString() : null,
                    'registered_at' => $registeredAt->format('Y-m-d H:i:s')
                ]);

            $this->db->execute($insert->getStatement(), $insert->getBindValues());

            $capeId = $this->db->getConnection()->lastInsertId();

            foreach ($itemInfo->tags->toArray() as $tag) {
                $insertTag = $this->db->builder->newInsert()
                    ->into('cape_tags')
                    ->cols([
                        'cape_id' => $capeId,
                        'tag' => $tag,
                    ]);
                $this->db->execute($insertTag->getStatement(), $insertTag->getBindValues());
            }

            $this->db->getConnection()->commit();

            return Result::success(null, new CapeData(
                $hash,
                Name::create($itemInfo->getName())->unwrap(),
                Description::create($itemInfo->getDescription())->unwrap(),
                $itemInfo->tags,
                $canAccessBank,
                $registeredAt,
                $itemInfo->getRarity()
            ));
        } catch (\Throwable $e) {
            $this->db->getConnection()->rollBack();
            return Result::error('Failed to persist cape: ' . $e->getMessage() . ' at ' . $e->getLine(), null);
        }
    }
}
