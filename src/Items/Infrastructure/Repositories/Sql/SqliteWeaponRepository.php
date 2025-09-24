<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\Sql;

use AqHub\Items\Domain\ValueObjects\{Description, Name, ItemTags, ItemInfo};
use AqHub\Shared\Domain\ValueObjects\{Result, StringIdentifier};
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\Repositories\WeaponRepository;
use AqHub\Shared\Infrastructure\Database\Connection;
use AqHub\Items\Domain\Enums\WeaponType;
use AqHub\Items\Domain\Entities\Weapon;
use AqHub\Shared\Domain\Enums\TagType;
use DomainException;

class SqliteWeaponRepository implements WeaponRepository
{
    public function __construct(private readonly Connection $db)
    {
    }

    /**
     * @return Result<Weapon|null>
     */
    public function persist(ItemInfo $itemInfo, WeaponType $type): Result
    {
        try {
            $this->db->getConnection()->beginTransaction();

            $hash = ItemIdentifierGenerator::generate($itemInfo, Weapon::class);
            if ($hash->isError()) {
                throw new DomainException('Failed to generate StringIdentifier: '. $hash->getMessage());
            }

            $hash = $hash->getData();

            if ($this->findByIdentifier($hash)->isSuccess()) {
                throw new DomainException('A Weapon with same identifier already exists: ' . $hash->getValue());
            }


            $query = 'INSERT INTO weapons (name, hash, description, type) VALUES (:name, :hash, :description, :type)';
            $this->db->execute($query, [
                'name' => $itemInfo->getName(),
                'hash' => $hash->getValue(),
                'description' => $itemInfo->getDescription(),
                'type' => $type->toString(),
            ]);

            $weaponId = $this->db->getConnection()->lastInsertId();

            foreach ($itemInfo->getTags()->toArray() as $tag) {
                $tagQuery = 'INSERT INTO weapon_tags (weapon_id, tag) VALUES (:weapon_id, :tag)';
                $this->db->execute($tagQuery, [
                    'weapon_id' => $weaponId,
                    'tag' => $tag,
                ]);
            }

            $this->db->getConnection()->commit();

            $weapon = Weapon::create($hash, $itemInfo, $type)->unwrap();

            return Result::success(null, $weapon);
        } catch (\Throwable $e) {
            $this->db->getConnection()->rollBack();
            return Result::error('Failed to persist weapon: ' . $e->getMessage() . ' at ' . $e->getLine(), null);
        }
    }

    /**
     * @return Result<Weapon|null>
     */
    public function findByIdentifier(StringIdentifier $identifier): Result
    {
        $query      = 'SELECT * FROM weapons WHERE hash = :hash LIMIT 1';
        $weaponData = $this->db->fetchOne($query, ['hash' => $identifier->getValue()]);

        if (!$weaponData) {
            return Result::error(null, null);
        }

        $tagsQuery = 'SELECT tag FROM weapon_tags WHERE weapon_id = :weapon_id';
        $tagsData  = $this->db->fetchAll($tagsQuery, ['weapon_id' => $weaponData['id']]);

        $tags = new ItemTags(array_map(fn ($row) => TagType::fromString($row['tag'])->unwrap(), $tagsData));

        $name        = Name::create($weaponData['name'])->unwrap();
        $description = Description::create($weaponData['description'])->unwrap();
        $itemInfo    = ItemInfo::create($name, $description, $tags)->unwrap();

        $weaponType = WeaponType::fromString($weaponData['type'])->unwrap();

        $weapon = Weapon::create(
            StringIdentifier::create($weaponData['hash'])->unwrap(),
            $itemInfo,
            $weaponType
        )->unwrap();

        return Result::success(null, $weapon);
    }
}
