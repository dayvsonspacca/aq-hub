<?php

declare(strict_types=1);

namespace AqWiki\Items\Infrastructure\Repositories\Sql;

use AqWiki\Items\Domain\ValueObjects\{Description, Name, ItemTags, ItemInfo};
use AqWiki\Shared\Domain\ValueObjects\{Identifier, Result};
use AqWiki\Items\Domain\Repositories\WeaponRepository;
use AqWiki\Shared\Infrastructure\Database\Connection;
use AqWiki\Items\Domain\Enums\WeaponType;
use AqWiki\Items\Domain\Entities\Weapon;
use AqWiki\Shared\Domain\Enums\TagType;
use DomainException;

class SqliteWeaponRepository implements WeaponRepository
{
    public function __construct(private readonly Connection $db) {}

    /**
     * @return Result<Identifier|null>
     */
    public function persist(ItemInfo $itemInfo, WeaponType $type): Result
    {
        try {
            $this->db->getConnection()->beginTransaction();

            if ($this->findByName($itemInfo->getName())->isSuccess()) {
                throw new DomainException('A Weapon with same name already exists: ' . $itemInfo->getName());
            }

            $query = 'INSERT INTO weapons (name, description, type) VALUES (:name, :description, :type)';
            $this->db->execute($query, [
                'name' => $itemInfo->getName(),
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

            return Result::success(null, Identifier::create((int) $weaponId)->unwrap());
        } catch (\Throwable $e) {
            $this->db->getConnection()->rollBack();
            return Result::error('Failed to persist weapon: ' . $e->getMessage() . ' at ' . $e->getLine(), null);
        }
    }

    /**
     * @return Result<Weapon|null>
     */
    public function findByName(string $name): Result
    {
        $query      = 'SELECT * FROM weapons WHERE name = :name LIMIT 1';
        $weaponData = $this->db->fetchOne($query, ['name' => $name]);

        if (!$weaponData) {
            return Result::error(null, null);
        }

        $tagsQuery = 'SELECT tag FROM weapon_tags WHERE weapon_id = :weapon_id';
        $tagsData  = $this->db->fetchAll($tagsQuery, ['weapon_id' => $weaponData['id']]);

        $tags = new ItemTags(array_map(fn($row) => TagType::fromString($row['tag'])->unwrap(), $tagsData));

        $name        = Name::create($weaponData['name'])->unwrap();
        $description = Description::create($weaponData['description'])->unwrap();
        $itemInfo    = ItemInfo::create($name, $description, $tags)->unwrap();

        $weaponType = WeaponType::fromString($weaponData['type'])->unwrap();

        $weapon = Weapon::create(
            Identifier::create((int)$weaponData['id'])->unwrap(),
            $itemInfo,
            $weaponType
        )->unwrap();

        return Result::success(null, $weapon);
    }
}
