<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Repositories\Sql;

use AqHub\Player\Domain\Entities\Player;
use AqHub\Player\Domain\Repositories\PlayerRepository;
use AqHub\Player\Domain\ValueObjects\{Level, Name, PlayerInventory};
use AqHub\Player\Infrastructure\Data\PlayerData;
use AqHub\Player\Infrastructure\Repositories\Filters\PlayerFilter;
use AqHub\Shared\Domain\ValueObjects\{IntIdentifier, Result};
use AqHub\Shared\Infrastructure\Database\Connection;
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

            $existing = $this->findByIdentifier($identifier);
            if ($existing->isSuccess() && $existing->getData() !== null) {
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
            return Result::error('Failed to persist player: ' . $e->getMessage(), null);
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

        $name   = Name::create($playerData['name'])->getData();
        $level  = Level::create((int) $playerData['level'])->getData();
        $player = Player::create($identifier, $name, $level, new PlayerInventory([], 999))->getData();

        return Result::success(null, $player);
    }

    /**
     * @return Result<array<PlayerData>>
     */
    public function findAll(PlayerFilter $filter): Result
    {
        $query = 'SELECT p.*,
              CASE WHEN pm.name IS NOT NULL THEN TRUE ELSE FALSE END AS mined
              FROM players p
              LEFT JOIN players_mined pm ON pm.name = p.name';

        $conditions = [];
        $params     = [];

        if (!is_null($filter->mined)) {
            $conditions[] = 'pm.id IS ' . ($filter->mined ? 'NOT NULL' : 'NULL');
        }

        if (!empty($conditions)) {
            $query .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $limit  = $filter->pageSize;
        $offset = ($filter->page - 1) * $filter->pageSize;

        $query .= ' ORDER BY p.id ASC LIMIT :limit OFFSET :offset';

        $params['limit']  = $limit;
        $params['offset'] = $offset;

        $playersData = $this->db->fetchAll($query, $params);

        if (!$playersData) {
            return Result::success(null, []);
        }

        $players = [];
        foreach ($playersData as $playerData) {
            $players[] = new PlayerData(
                IntIdentifier::create((int)$playerData['id'])->unwrap(),
                Name::create($playerData['name'])->unwrap(),
                Level::create((int)$playerData['level'])->unwrap(),
                new \DateTime($playerData['registered_at']),
                (bool) $playerData['mined']
            );
        }

        return Result::success(null, $players);
    }

    public function markAsMined(Name $name): Result
    {
        $query      = 'SELECT * FROM players_mined WHERE name = :name';
        $playerData = $this->db->fetchOne($query, ['name' => $name->value]);

        if (!$playerData) {
            $query = 'INSERT INTO players_mined (name, mined_at) VALUES (:name, NOW())';
            $this->db->execute($query, [
                'name' => $name->value
            ]);

            return Result::success(null, null);
        }

        return Result::error('The player ' . $name->value . ' is already mined.', null);
    }
}
