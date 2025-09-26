<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\Sql;

use AqHub\Items\Domain\Entities\Armor;
use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Items\Domain\Repositories\Data\ArmorData;
use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Shared\Domain\ValueObjects\{Result, StringIdentifier};
use AqHub\Shared\Infrastructure\Database\Connection;
use DateTime;
use DomainException;

class SqlArmorRepository implements ArmorRepository
{
    public function __construct(private readonly Connection $db) {}

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
        $tags     = new ItemTags(array_map(fn($row) => TagType::fromString($row['tag'])->unwrap(), $tagsData));

        $name        = Name::create($armorData['name'])->unwrap();
        $description = Description::create($armorData['description'])->unwrap();

        return Result::success(
            null,
            new ArmorData(
                $identifier,
                $name,
                $description,
                $tags,
                new DateTime($armorData['registered_at'])
            )
        );
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
                    'registered_at' => $registeredAt->getTimestamp()
                ]);

            $this->db->execute($insert->getStatement());

            $armorId = $this->db->getConnection()->lastInsertId();

            foreach ($itemInfo->getTags()->toArray() as $tag) {
                $insertTag = $this->db->builder->newInsert()
                    ->into('armor_tags')
                    ->cols([
                        'armor_id' => $armorId,
                        'tag' => $tag
                    ]);
                $this->db->execute($insertTag->getStatement());
            }

            $this->db->getConnection()->commit();

            return Result::success(null, new ArmorData(
                $hash,
                Name::create($itemInfo->getName())->unwrap(),
                Description::create($itemInfo->getDescription())->unwrap(),
                $itemInfo->getTags(),
                $registeredAt
            ));
        } catch (\Throwable $e) {
            $this->db->getConnection()->rollBack();
            return Result::error('Failed to persist armor: ' . $e->getMessage() . ' at ' . $e->getLine(), null);
        }
    }
}
