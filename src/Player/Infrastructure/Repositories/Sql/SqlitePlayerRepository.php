<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\Sql;

use AqHub\Shared\Domain\ValueObjects\{Identifier, Result};
use AqHub\Player\Domain\Repositories\PlayerRepository;
use AqHub\Player\Domain\ValueObjects\PlayerInventory;
use AqHub\Shared\Infrastructure\Database\Connection;
use AqHub\Player\Domain\Entities\Player;
use AqHub\Player\Domain\ValueObjects\Name;
use DomainException;

class SqlitePlayerRepository implements PlayerRepository
{
    public function __construct(private readonly Connection $db)
    {
    }

    /**
     * @return Result<Identifier|null>
     */
    public function persist(Identifier $identifier, Name $name): Result
    {
        try {
            $this->db->getConnection()->beginTransaction();

            if ($this->findByIdentifier($identifier)) {
                throw new DomainException('A player with same id already exists: ' . $identifier->getValue());
            }

            $query = 'INSERT INTO players (id, name) VALUES (:id, :name)';
            $this->db->execute($query, [
                'id' => $identifier->getValue(),
                'name' => $name->value
            ]);

            $this->db->getConnection()->commit();

            return Result::success(null, $identifier);
        } catch (\Throwable $e) {
            $this->db->getConnection()->rollBack();
            return Result::error('Failed to persist weapon: ' . $e->getMessage() . ' at ' . $e->getLine(), null);
        }
    }

    public function findByIdentifier(Identifier $identifier): Result
    {
        $query      = 'SELECT * FROM players WHERE id = :id LIMIT 1';
        $playerData = $this->db->fetchOne($query, ['id' => $identifier->getValue()]);

        if (!$playerData) {
            return Result::error(null, null);
        }

        $name = Name::create($playerData['name'])->getData();

        $player = Player::create($identifier, $name, 1, new PlayerInventory([], 999))->getData();

        return Result::success(null, $player);
    }
}
