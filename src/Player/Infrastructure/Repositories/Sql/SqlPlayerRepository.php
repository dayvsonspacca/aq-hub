<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Repositories\Sql;

use AqHub\Shared\Domain\ValueObjects\{IntIdentifier, Result};
use AqHub\Player\Domain\Repositories\PlayerRepository;
use AqHub\Player\Domain\ValueObjects\PlayerInventory;
use AqHub\Shared\Infrastructure\Database\Connection;
use AqHub\Player\Domain\ValueObjects\{Level, Name};
use AqHub\Player\Domain\Entities\Player;
use DomainException;

class SqlPlayerRepository implements PlayerRepository
{
    public function __construct(private readonly Connection $db)
    {
    }

    /**
     * @return Result<Player|null>
     */
    public function persist(IntIdentifier $identifier, Name $name, Level $level): Result
    {
        try {
            $this->db->getConnection()->beginTransaction();

            if ($this->findByIdentifier($identifier)->isSuccess()) {
                throw new DomainException('A player with same id already exists: ' . $identifier->getValue());
            }

            $query = 'INSERT INTO players (id, name, level) VALUES (:id, :name, :level)';
            $this->db->execute($query, [
                'id' => $identifier->getValue(),
                'name' => $name->value,
                'level' => $level->value
            ]);

            $player = Player::create($identifier, $name, $level, new PlayerInventory([], 999))->unwrap();

            $this->db->getConnection()->commit();

            return Result::success(null, $player);
        } catch (\Throwable $e) {
            $this->db->getConnection()->rollBack();
            return Result::error('Failed to persist player: ' . $e->getMessage() . ' at ' . $e->getLine(), null);
        }
    }

    /**
     * @return Result<Player|null>
     */
    public function findByIdentifier(IntIdentifier $identifier): Result
    {
        $query      = 'SELECT * FROM players WHERE id = :id LIMIT 1';
        $playerData = $this->db->fetchOne($query, ['id' => $identifier->getValue()]);

        if (!$playerData) {
            return Result::error(null, null);
        }

        $name = Name::create($playerData['name'])->getData();

        $player = Player::create($identifier, $name, Level::create((int) $playerData['level'])->getData(), new PlayerInventory([], 999))->getData();

        return Result::success(null, $player);
    }
}
