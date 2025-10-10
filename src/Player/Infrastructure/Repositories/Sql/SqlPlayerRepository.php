<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Repositories\Sql;

use AqHub\Player\Domain\Repositories\Data\PlayerData;
use AqHub\Player\Domain\Repositories\Filters\PlayerFilter;
use AqHub\Player\Domain\Repositories\PlayerRepository;
use AqHub\Player\Domain\ValueObjects\{Level, Name};
use AqHub\Shared\Domain\ValueObjects\{IntIdentifier, Result};
use AqHub\Shared\Infrastructure\Database\Connection;
use DateTime;
use DomainException;

class SqlPlayerRepository implements PlayerRepository
{
    public function __construct(private readonly Connection $db)
    {
    }

    /**
     * @return Result<PlayerData|null>
     */
    public function persist(IntIdentifier $identifier, Name $name, Level $level): Result
    {
        try {
            $this->db->getConnection()->beginTransaction();

            $existing = $this->findByIdentifier($identifier);
            if ($existing->isSuccess() && $existing->getData() !== null) {
                throw new DomainException('A player with same id already exists: ' . $identifier->getValue());
            }

            $insert = $this->db->builder->newInsert()
                ->into('players')
                ->cols([
                    'id' => $identifier->getValue(),
                    'name' => $name->value,
                    'level' => $level->value
                ]);

            $this->db->execute($insert->getStatement(), $insert->getBindValues());

            $playerData = new PlayerData(
                $identifier,
                $name,
                $level,
                new DateTime(), 
                false
            );

            $this->db->getConnection()->commit();

            return Result::success(null, $playerData);
        } catch (\Throwable $e) {
            $this->db->getConnection()->rollBack();
            return Result::error('Failed to persist player: ' . $e->getMessage(), null);
        }
    }

    public function findByIdentifier(IntIdentifier $identifier): Result
    {
        $select = $this->db->builder->newSelect()
            ->from('players as p')
            ->cols(['p.*', 'FALSE AS mined'])
            ->where('p.id = :id', ['id' => $identifier->getValue()])
            ->limit(1);

        $playerData = $this->db->fetchOne($select->getStatement(), $select->getBindValues());

        if (!$playerData) {
            return Result::error(null, null);
        }

        return Result::success(null, $this->buildPlayerData((array)$playerData));
    }

    public function findAll(PlayerFilter $filter): Result
    {
        $select = $this->db->builder->newSelect()
            ->from('players as p')
            ->cols(['p.*', 'CASE WHEN pm.name IS NOT NULL THEN TRUE ELSE FALSE END AS mined'])
            ->join('LEFT', 'players_mined AS pm', 'pm.name = p.name');

        if (!is_null($filter->mined)) {
            $condition = 'pm.id IS ' . ($filter->mined ? 'NOT NULL' : 'NULL');
            $select->where($condition);
        }

        $limit  = $filter->pageSize;
        $offset = ($filter->page - 1) * $filter->pageSize;

        $select->orderBy(['p.id ASC'])
            ->limit($limit)
            ->offset($offset);

        $playersData = $this->db->fetchAll($select->getStatement(), $select->getBindValues());

        if (!$playersData) {
            return Result::success(null, []);
        }

        $players = array_map(function (array $playerData) {
            return $this->buildPlayerData($playerData);
        }, $playersData);

        return Result::success(null, $players);
    }

    public function markAsMined(Name $name): Result
    {
        $select = $this->db->builder->newSelect()
            ->from('players_mined')
            ->cols(['*'])
            ->where('name = :name', ['name' => $name->value]);

        $playerData = $this->db->fetchOne($select->getStatement(), $select->getBindValues());

        if (!$playerData) {
            $insert = $this->db->builder->newInsert()
                ->into('players_mined')
                ->cols(['name' => $name->value, 'mined_at' => 'NOW()']);

            $this->db->execute($insert->getStatement(), $insert->getBindValues());

            return Result::success(null, null);
        }

        return Result::error('The player ' . $name->value . ' is already mined.', null);
    }

    private function buildPlayerData(array $playerData): PlayerData
    {
        return new PlayerData(
            IntIdentifier::create((int)$playerData['id'])->unwrap(),
            Name::create($playerData['name'])->unwrap(),
            Level::create((int)$playerData['level'])->unwrap(),
            new DateTime($playerData['registered_at']),
            (bool) $playerData['mined']
        );
    }
}
