<?php

declare(strict_types=1);

namespace AqWiki\Infrastructure\Repositories;

use AqWiki\Domain\{Entities, Repositories};

final class PDOSqliteQuestRepository implements Repositories\QuestRepositoryInterface
{
    private \PDO $database;

    public function __construct()
    {
        $this->database = new \PDO('sqlite:database.sqlite');
    }

    public function getById(string $guid): ?Entities\Quest
    {
        $statement = $this->database->prepare(
            'SELECT * FROM quests WHERE guid = :guid'
        );

        $statement->execute(['guid' => $guid]);
        $quest = $statement->fetch(\PDO::FETCH_ASSOC);

        return is_null($quest)
            ? null
            : new Entities\Quest(
                name: $quest['name'],
                location: $quest['location'],
                requirements: unserialize($quest['requirements']),
                rewards: unserialize($quest['rewards'])
            );
    }

    public function persist(Entities\Quest $quest)
    {
        $statement = $this->database->prepare(
            'INSERT INTO quests (name, location, requirements, rewards) VALUES (:name, :location, :requirements, :rewards)'
        );

        $statement->execute([
            'name'         => $quest->name,
            'location'     => $quest->location,
            'requirements' => serialize($quest->requirements),
            'rewards'      => serialize($quest->rewards)
        ]);
    }
}
