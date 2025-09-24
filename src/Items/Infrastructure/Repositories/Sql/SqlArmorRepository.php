<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\Sql;

use AqHub\Items\Domain\Entities\Armor;
use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Shared\Domain\ValueObjects\{Result, StringIdentifier};
use AqHub\Shared\Infrastructure\Database\Connection;
use DomainException;

class SqlArmorRepository implements ArmorRepository
{
    public function __construct(private readonly Connection $db)
    {
    }

    /**
     * @return Result<Armor|null>
     */
    public function persist(ItemInfo $itemInfo): Result
    {
        try {
            $this->db->getConnection()->beginTransaction();

            $hash = ItemIdentifierGenerator::generate($itemInfo, Armor::class);
            if ($hash->isError()) {
                throw new DomainException('Failed to generate StringIdentifier: '. $hash->getMessage());
            }

            $hash = $hash->getData();

            if ($this->findByIdentifier($hash)->isSuccess()) {
                throw new DomainException('An Armor with same identifier already exists: ' . $hash->getValue());
            }

            $query = 'INSERT INTO armors (name, hash, description) VALUES (:name, :hash, :description)';
            $this->db->execute($query, [
                'name' => $itemInfo->getName(),
                'hash' => $hash->getValue(),
                'description' => $itemInfo->getDescription(),
            ]);

            $armorId = $this->db->getConnection()->lastInsertId();

            foreach ($itemInfo->getTags()->toArray() as $tag) {
                $tagQuery = 'INSERT INTO armor_tags (armor_id, tag) VALUES (:armor_id, :tag)';
                $this->db->execute($tagQuery, [
                    'armor_id' => $armorId,
                    'tag' => $tag,
                ]);
            }

            $this->db->getConnection()->commit();

            $armor = Armor::create($hash, $itemInfo)->unwrap();

            return Result::success(null, $armor);
        } catch (\Throwable $e) {
            $this->db->getConnection()->rollBack();
            return Result::error('Failed to persist armor: ' . $e->getMessage() . ' at ' . $e->getLine(), null);
        }
    }

    /**
     * @return Result<Armor|null>
     */
    public function findByIdentifier(StringIdentifier $identifier): Result
    {
        $query      = 'SELECT * FROM armors WHERE hash = :hash LIMIT 1';
        $armorData  = $this->db->fetchOne($query, ['hash' => $identifier->getValue()]);

        if (!$armorData) {
            return Result::error(null, null);
        }

        $tagsQuery = 'SELECT tag FROM armor_tags WHERE armor_id = :armor_id';
        $tagsData  = $this->db->fetchAll($tagsQuery, ['armor_id' => $armorData['id']]);

        $tags = new ItemTags(array_map(fn ($row) => TagType::fromString($row['tag'])->unwrap(), $tagsData));

        $name        = Name::create($armorData['name'])->unwrap();
        $description = Description::create($armorData['description'])->unwrap();
        $itemInfo    = ItemInfo::create($name, $description, $tags)->unwrap();

        $armor = Armor::create(
            StringIdentifier::create($armorData['hash'])->unwrap(),
            $itemInfo
        )->unwrap();

        return Result::success(null, $armor);
    }
}
