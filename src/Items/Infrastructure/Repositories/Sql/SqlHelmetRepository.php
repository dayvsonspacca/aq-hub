<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\Sql;

use AqHub\Items\Domain\Entities\Helmet;
use AqHub\Items\Domain\Repositories\HelmetRepository;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Items\Domain\Repositories\Data\HelmetData;
use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Shared\Domain\ValueObjects\{Result, StringIdentifier};
use AqHub\Shared\Infrastructure\Database\Connection;
use DateTime;
use DomainException;

class SqlHelmetRepository implements HelmetRepository
{
    public function __construct(private readonly Connection $db) {}

    /**
     * @return Result<HelmetData|null>
     */
    public function findByIdentifier(StringIdentifier $identifier): Result
    {
        $select = $this->db->builder->newSelect()
            ->from('helmets')
            ->cols(['*'])
            ->where('id = :id')
            ->bindValue('id', $identifier->getValue());

        $query = $select->getStatement();

        $helmetData = $this->db->execute($query);
        if (!$helmetData) {
            return Result::error(null, null);
        }

        $name        = Name::create($helmetData['name'])->unwrap();
        $description = Description::create($helmetData['description'])->unwrap();

        $select = $this->db->builder->newSelect()
            ->from('helmet_tags')
            ->cols(['tag'])
            ->where('helmet_id = :helmet_id')
            ->bindValue('helmet_id', $identifier->getValue());

        $tagsData  = $this->db->fetchAll($select->getStatement(), ['helmet_id' => $identifier->getValue()]);
        $tags      = new ItemTags(array_map(fn($row) => TagType::fromString($row['tag'])->unwrap(), $tagsData));

        return Result::success(
            null,
            new HelmetData(
                $identifier,
                $name,
                $description,
                $tags,
                new DateTime($helmetData['registered_at'])
            )
        );
    }

    /**
     * @return Result<HelmetData|null>
     */
    public function persist(ItemInfo $itemInfo): Result
    {
        try {
            $this->db->getConnection()->beginTransaction();

            $hash = ItemIdentifierGenerator::generate($itemInfo, Helmet::class);
            if ($hash->isError()) {
                throw new DomainException('Failed to generate StringIdentifier: ' . $hash->getMessage());
            }

            $hash = $hash->getData();

            if ($this->findByIdentifier($hash)->isSuccess()) {
                throw new DomainException('A Helmet with same identifier already exists: ' . $hash->getValue());
            }

            $registeredAt = new DateTime();

            $insert = $this->db->builder->newInsert()
                ->into('helmets')
                ->cols([
                    'name' => $itemInfo->getName(),
                    'hash' => $hash->getValue(),
                    'description' => $itemInfo->getDescription(),
                    'registered_at' => $registeredAt->format('Y-m-d H:i:s')
                ]);

            $this->db->execute($insert->getStatement(), $insert->getBindValues());

            $helmetId = $this->db->getConnection()->lastInsertId();

            foreach ($itemInfo->tags->toArray() as $tag) {
                $insertTag = $this->db->builder->newInsert()
                    ->into('helmet_tags')
                    ->cols([
                        'helmet_id' => $helmetId,
                        'tag' => $tag,
                    ]);
                $this->db->execute($insertTag->getStatement(), $insertTag->getBindValues());
            }

            $this->db->getConnection()->commit();

            return Result::success(null, new HelmetData(
                $hash,
                Name::create($itemInfo->getName())->unwrap(),
                Description::create($itemInfo->getDescription())->unwrap(),
                $itemInfo->tags,
                $registeredAt
            ));
        } catch (\Throwable $e) {
            $this->db->getConnection()->rollBack();
            return Result::error('Failed to persist helmet: ' . $e->getMessage() . ' at ' . $e->getLine(), null);
        }
    }
}
