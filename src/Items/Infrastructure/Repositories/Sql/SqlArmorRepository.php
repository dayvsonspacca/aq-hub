<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\Sql;

use AqHub\Items\Domain\Entities\Armor;
use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Domain\Repositories\Data\ArmorData;
use AqHub\Items\Domain\Repositories\Filters\ArmorFilter;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Shared\Domain\ValueObjects\{Result, StringIdentifier};
use AqHub\Shared\Infrastructure\Database\Connection;
use DateTime;
use DomainException;

class SqlArmorRepository implements ArmorRepository
{
    public function __construct(private readonly Connection $db)
    {
    }

    /**
     * @return Result<ArmorData|null>
     */
    public function findByIdentifier(StringIdentifier $identifier): Result
    {
        $select = $this->db->builder->newSelect()
            ->from('armors')
            ->cols(['*'])
            ->where('hash = :hash')
            ->bindValue('hash', $identifier->getValue());

        $armorData = $this->db->fetchOne($select->getStatement(), ['hash' => $identifier->getValue()]);

        if (!$armorData) {
            return Result::error(null, null);
        }

        $tagsSelect = $this->db->builder->newSelect()
            ->from('armor_tags')
            ->cols(['tag'])
            ->where('armor_id = :armor_id')
            ->bindValue('armor_id', $armorData['id']);

        $tagsData = $this->db->fetchAll($tagsSelect->getStatement(), ['armor_id' => $armorData['id']]);
        $tags     = new ItemTags(array_map(fn ($row) => TagType::fromString($row['tag'])->unwrap(), $tagsData));

        $name        = Name::create($armorData['name'])->unwrap();
        $description = Description::create($armorData['description'])->unwrap();

        $rarity = ItemRarity::fromString($armorData['rarity'] ?? '');
        $rarity = $rarity->isError() ? null : $rarity->getData();

        return Result::success(
            null,
            new ArmorData(
                $identifier,
                $name,
                $description,
                $tags,
                new DateTime($armorData['registered_at']),
                $rarity ?? null
            )
        );
    }

    /**
     * @return Result<array<ArmorData>>
     */
    public function findAll(ArmorFilter $filter): Result
    {
        $select = $this->db->builder->newSelect()
            ->from('armors as a')
            ->cols(['a.*']);

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

        $limit  = $filter->pageSize;
        $offset = ($filter->page - 1) * $filter->pageSize;

        $select->limit($limit)->offset($offset)->orderBy(['id ASC']);

        $armorsData = $this->db->fetchAll($select->getStatement(), $select->getBindValues());

        if (!$armorsData) {
            return Result::success(null, []);
        }

        $armors = [];

        foreach ($armorsData as $armorData) {
            $armorId = (int)$armorData['id'];

            $tagsSelect = $this->db->builder->newSelect()
                ->from('armor_tags')
                ->cols(['tag'])
                ->where('armor_id = :armor_id_' . $armorId)
                ->bindValue('armor_id_' . $armorId, $armorId);

            $tagsData = $this->db->fetchAll($tagsSelect->getStatement(), ['armor_id_' . $armorId => $armorId]);
            $tags     = new ItemTags(array_map(fn ($row) => TagType::fromString($row['tag'])->unwrap(), $tagsData));

            $rarity = ItemRarity::fromString($armorData['rarity'] ?? '');
            $rarity = $rarity->isError() ? null : $rarity->getData();

            $name        = Name::create($armorData['name'])->unwrap();
            $description = Description::create($armorData['description'])->unwrap();

            $armors[] = new ArmorData(
                StringIdentifier::create($armorData['hash'])->unwrap(),
                $name,
                $description,
                $tags,
                new DateTime($armorData['registered_at']),
                $rarity
            );
        }

        return Result::success(null, $armors);
    }

    /**
     * @return Result<ArmorData|null>
     */
    public function persist(ItemInfo $itemInfo): Result
    {
        try {
            $this->db->getConnection()->beginTransaction();

            $hash = ItemIdentifierGenerator::generate($itemInfo, Armor::class);
            if ($hash->isError()) {
                throw new DomainException('Failed to generate StringIdentifier: ' . $hash->getMessage());
            }

            $hash = $hash->getData();

            if ($this->findByIdentifier($hash)->isSuccess()) {
                throw new DomainException('An Armor with same identifier already exists: ' . $hash->getValue());
            }

            $registeredAt = new DateTime();

            $insert = $this->db->builder->newInsert()
                ->into('armors')
                ->cols([
                    'name' => $itemInfo->getName(),
                    'hash' => $hash->getValue(),
                    'description' => $itemInfo->getDescription(),
                    'rarity' => $itemInfo->getRarity() ? $itemInfo->getRarity()->toString() : null,
                    'registered_at' => $registeredAt->format('Y-m-d H:i:s')
                ]);

            $this->db->execute($insert->getStatement(), $insert->getBindValues());

            $armorId = $this->db->getConnection()->lastInsertId();

            foreach ($itemInfo->tags->toArray() as $tag) {
                $insertTag = $this->db->builder->newInsert()
                    ->into('armor_tags')
                    ->cols([
                        'armor_id' => $armorId,
                        'tag' => $tag
                    ]);
                $this->db->execute($insertTag->getStatement(), $insertTag->getBindValues());
            }

            $this->db->getConnection()->commit();

            return Result::success(null, new ArmorData(
                $hash,
                Name::create($itemInfo->getName())->unwrap(),
                Description::create($itemInfo->getDescription())->unwrap(),
                $itemInfo->tags,
                $registeredAt,
                $itemInfo->getRarity()
            ));
        } catch (\Throwable $e) {
            $this->db->getConnection()->rollBack();
            return Result::error('Failed to persist armor: ' . $e->getMessage() . ' at ' . $e->getLine(), null);
        }
    }
}
