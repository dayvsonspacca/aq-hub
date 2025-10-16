<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\Pgsql;

use AqHub\Core\Infrastructure\Database\PgsqlConnection;
use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Domain\Repositories\Data\ArmorData;
use AqHub\Items\Domain\Repositories\Filters\ArmorFilter;
use AqHub\Items\Domain\ValueObjects\Description;
use AqHub\Items\Domain\ValueObjects\ItemTags;
use AqHub\Items\Domain\ValueObjects\Name;
use AqHub\Shared\Domain\Abstractions\Filter;
use AqHub\Shared\Domain\Contracts\Identifier;
use AqHub\Shared\Domain\Enums\ItemTag;
use AqHub\Shared\Domain\ValueObjects\StringIdentifier;
use Aura\SqlQuery\QueryFactory;
use DateTime;
use PDO;

class PgsqlArmorRepository implements ArmorRepository
{
    public function __construct(
        private readonly PgsqlConnection $db,
        private readonly QueryFactory $query
    ) {}

    public function hydrate(array $data): ArmorData
    {
        $name         = Name::create($data['name'])->unwrap();
        $description  = Description::create($data['description'])->unwrap();
        $identifier   = StringIdentifier::create($data['hash'])->unwrap();
        $tags         = new ItemTags(array_map(fn(string $tag) => ItemTag::fromString($tag)->unwrap(), $data['tags']));
        $registeredAt = new DateTime($data['registered_at']);

        $rarity = ItemRarity::fromString($data['rarity'] ?? '');
        $rarity = $rarity->isError() ? null : $rarity->unwrap();
        
        return new ArmorData(
            $identifier,
            $name,
            $description,
            $tags,
            $registeredAt,
            $rarity
        );
    }

    /**
     * @param StringIdentifier $identifier
     */
    public function findByIdentifier(Identifier $identifier)
    {
        $select = $this->query->newSelect();

        $select->from('armors')
            ->cols(['*'])
            ->where('hash = :hash', ['hash' => $identifier->getValue()]);

        $statement = $this->db->connection->prepare($select->getStatement());
        $statement->execute($select->getBindValues());
        
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            return null;
        }

        return $this->hydrate($result);
    }


    /**
     * @param ArmorFilter $filter
     * @return ArmorData[]
     */
    public function findAll(Filter $filter): array
    {
        return [];
    }
}
