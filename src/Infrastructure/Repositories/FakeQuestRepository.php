<?php

declare(strict_types=1);

namespace AqWiki\Infrastructure\Repositories;

use AqWiki\Domain\{Entities, Repositories, ValueObjects};
use AqWiki\Domain\Entities\Quest;

final class FakeQuestRepository implements Repositories\QuestRepositoryInterface
{
    private array $database;

    public function __construct()
    {
        $this->database = [
            'a-dark-knight' => new Entities\Quest(
                name: 'A Dark Knight',
                location: 'Hollowdeep',
                requirements: (new ValueObjects\QuestRequirements([new ValueObjects\LevelRequirement(65)]))
            )
        ];
    }

    public function getById(string $guid): ?Entities\Quest
    {
        return isset($this->database[$guid])
            ? $this->database[$guid]
            : null;
    }

    public function persist(Quest $quest)
    {
        $this->database[$quest->name] = $quest;
    }
}
