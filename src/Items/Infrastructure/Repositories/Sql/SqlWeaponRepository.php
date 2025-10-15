<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\Sql;

use AqHub\Items\Domain\Entities\Weapon;
use AqHub\Items\Domain\Enums\WeaponType;
use AqHub\Items\Domain\Repositories\Data\WeaponData;
use AqHub\Items\Domain\Repositories\WeaponRepository;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Shared\Domain\Enums\ItemTag;
use AqHub\Shared\Domain\ValueObjects\{Result, StringIdentifier};
use AqHub\Shared\Infrastructure\Database\Connection;
use DateTime;
use DomainException;

class SqlWeaponRepository implements WeaponRepository
{
    public function __construct(private readonly Connection $db)
    {
    }

    /**
     * @return Result<WeaponData|null>
     */
    public function findByIdentifier(StringIdentifier $identifier): Result
    {
        $select = $this->db->builder->newSelect()
            ->from('weapons')
            ->cols(['*'])
            ->where('hash = :hash')
            ->bindValue('hash', $identifier->getValue());

        $weaponData = $this->db->fetchOne($select->getStatement(), ['hash' => $identifier->getValue()]);

        if (!$weaponData) {
            return Result::error(null, null);
        }

        $tagsSelect = $this->db->builder->newSelect()
            ->from('weapon_tags')
            ->cols(['tag'])
            ->where('weapon_id = :weapon_id')
            ->bindValue('weapon_id', $weaponData['id']);

        $tagsData = $this->db->fetchAll($tagsSelect->getStatement(), ['weapon_id' => $weaponData['id']]);
        $tags     = new ItemTags(array_map(fn ($row) => ItemTag::fromString($row['tag'])->unwrap(), $tagsData));

        $name        = Name::create($weaponData['name'])->unwrap();
        $description = Description::create($weaponData['description'])->unwrap();
        $weaponType  = WeaponType::fromString($weaponData['type'])->unwrap();

        return Result::success(
            null,
            new WeaponData(
                $identifier,
                $name,
                $description,
                $tags,
                $weaponType,
                new DateTime($weaponData['registered_at'])
            )
        );
    }

    /**
     * @return Result<WeaponData|null>
     */
    public function persist(ItemInfo $itemInfo, WeaponType $type): Result
    {
        try {
            $this->db->getConnection()->beginTransaction();

            $hash = ItemIdentifierGenerator::generate($itemInfo, Weapon::class);
            if ($hash->isError()) {
                throw new DomainException('Failed to generate StringIdentifier: ' . $hash->getMessage());
            }

            $hash = $hash->getData();

            if ($this->findByIdentifier($hash)->isSuccess()) {
                throw new DomainException('A Weapon with same identifier already exists: ' . $hash->getValue());
            }

            $registeredAt = new DateTime();

            $insert = $this->db->builder->newInsert()
                ->into('weapons')
                ->cols([
                    'name' => $itemInfo->getName(),
                    'hash' => $hash->getValue(),
                    'description' => $itemInfo->getDescription(),
                    'type' => $type->toString(),
                    'rarity' => $itemInfo->getRarity() ? $itemInfo->getRarity()->toString() : null,
                    'registered_at' => $registeredAt->format('Y-m-d H:i:s')
                ]);

            $this->db->execute($insert->getStatement(), $insert->getBindValues());

            $weaponId = $this->db->getConnection()->lastInsertId();

            foreach ($itemInfo->tags->toArray() as $tag) {
                $insertTag = $this->db->builder->newInsert()
                    ->into('weapon_tags')
                    ->cols([
                        'weapon_id' => $weaponId,
                        'tag' => $tag
                    ]);
                $this->db->execute($insertTag->getStatement(), $insertTag->getBindValues());
            }

            $this->db->getConnection()->commit();

            return Result::success(null, new WeaponData(
                $hash,
                Name::create($itemInfo->getName())->unwrap(),
                Description::create($itemInfo->getDescription())->unwrap(),
                $itemInfo->tags,
                $type,
                $registeredAt
            ));
        } catch (\Throwable $e) {
            $this->db->getConnection()->rollBack();
            return Result::error('Failed to persist weapon: ' . $e->getMessage() . ' at ' . $e->getLine(), null);
        }
    }
}
