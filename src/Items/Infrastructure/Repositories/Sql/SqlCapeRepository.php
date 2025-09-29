<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\Sql;

use AqHub\Items\Domain\Entities\Cape;
use AqHub\Items\Domain\Repositories\CapeRepository;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Items\Domain\Repositories\Data\CapeData;
use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Shared\Domain\ValueObjects\{Result, StringIdentifier};
use AqHub\Shared\Infrastructure\Database\Connection;
use DateTime;
use DomainException;

class SqlCapeRepository implements CapeRepository
{
    public function __construct(private readonly Connection $db) {}

    /**
     * @return Result<CapeData|null>
     */
    public function findByIdentifier(StringIdentifier $identifier): Result
    {
        $select = $this->db->builder->newSelect()
            ->from('capes')
            ->cols(['*'])
            ->where('id = :id')
            ->bindValue('id', $identifier->getValue());

        $query = $select->getStatement();

        $capeData = $this->db->execute($query);
        if (!$capeData) {
            return Result::error(null, null);
        }

        $name        = Name::create($capeData['name'])->unwrap();
        $description = Description::create($capeData['description'])->unwrap();
        $canAccessBank = (bool) $capeData['can_access_bank'];

        $select = $this->db->builder->newSelect()
            ->from('cape_tags')
            ->cols(['tag'])
            ->where('cape_id = :cape_id')
            ->bindValue('cape_id', $identifier->getValue());

        $tagsData  = $this->db->fetchAll($select->getStatement(), ['cape_id' => $identifier->getValue()]);
        $tags      = new ItemTags(array_map(fn($row) => TagType::fromString($row['tag'])->unwrap(), $tagsData));

        return Result::success(
            null,
            new CapeData(
                $identifier,
                $name,
                $description,
                $tags,
                $canAccessBank,
                new DateTime($capeData['registered_at'])
            )
        );
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
                    'can_access_bank' => $canAccessBank,
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
                $registeredAt
            ));
        } catch (\Throwable $e) {
            $this->db->getConnection()->rollBack();
            return Result::error('Failed to persist cape: ' . $e->getMessage() . ' at ' . $e->getLine(), null);
        }
    }
}
