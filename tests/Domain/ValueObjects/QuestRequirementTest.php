<?php

declare(strict_types=1);

namespace Tests\Domain\ValueObjects;

use AqWiki\Domain\{ValueObjects, Entities, Repositories};
use AqWiki\Infrastructure\Repositories\Fakes\FakeQuestRepository;
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\TestCase;

final class QuestRequirementTest extends TestCase
{
    private Entities\Player $player;
    private Repositories\QuestRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->player = new Entities\Player(65, []);
        $this->repository = new FakeQuestRepository();
    }

    #[Test]
    public function fails_when_player_does_not_meet_first_requirement()
    {
        $requirements = new ValueObjects\QuestRequirements();
        $requirements->add(new ValueObjects\QuestRequirement($this->repository->getById('a-dark-knight')));
        $requirements->add(new ValueObjects\LevelRequirement(75));

        $quest = new Entities\Quest(
            name: 'A Dark Knight Rises',
            location: null,
            requirements: $requirements
        );

        foreach ($quest->requirements as $requeriment) {
            if (!$requeriment->pass($this->player)) {
                $this->assertTrue(true);
                return;
            }
        }

        $this->fail();
    }

    #[Test]
    public function pass_when_player_does_meet_requirements()
    {
        $requirements = new ValueObjects\QuestRequirements();
        $requirements->add(new ValueObjects\QuestRequirement($this->repository->getById('a-dark-knight')));
        $requirements->add(new ValueObjects\LevelRequirement(65));

        $quest = new Entities\Quest(
            name: 'A Dark Knight Rises',
            location: null,
            requirements: $requirements
        );

        foreach ($quest->requirements as $requeriment) {
            if (!$requeriment->pass($this->player)) {
                $this->fail();
            }
        }

        $this->assertTrue(true);
    }
}
