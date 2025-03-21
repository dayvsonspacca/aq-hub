<?php

declare(strict_types=1);

namespace AqWiki\Infrastructure\Repositories;

use AqWiki\Domain\{Entities, Repositories, ValueObjects};
use AqWiki\Domain\ValueObjects\QuestRequirements;

final class FakeQuestRepository implements Repositories\QuestRepositoryInterface
{
    public function getById(string $guid): ?Entities\Quest
    {
        return $this->fakeDatabase($guid);
    }

    public function findRequirements(string $guid): QuestRequirements
    {
        $requirements = new ValueObjects\QuestRequirements();

        foreach ([new ValueObjects\LevelRequirement(65)] as $requirement) {
            $requirements->add($requirement);
        }

        return $requirements;
    }

    private function fakeDatabase(string $guid): ?Entities\Quest
    {
        $quests = [
            'a-dark-knight' => new Entities\Quest(
                name: 'A Dark Knight',
                location: 'Hollowdeep',
                requirements: $this->findRequirements('a-dark-knight')
            )
        ];

        return $quests[$guid] ?? null;
    }
}
