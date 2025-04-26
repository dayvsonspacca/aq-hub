<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObjects;

use AqWiki\Domain\{ValueObjects, Entities};
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

final class QuestRequirementTest extends TestCase
{
    private Entities\Player $player;
    private Entities\Quest $quest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->player = new Entities\Player(65, []);
        $this->quest = new Entities\Quest(
            guid: 'awesome-quest-id',
            name: 'Awesome Quest',
            requirements: new ValueObjects\QuestRequirements([])
        );
    }

    #[Test]
    public function fails_when_player_does_not_meet_first_requirement(): void
    {
        $requirements = new ValueObjects\QuestRequirements();
        $requirements->add(new ValueObjects\QuestRequirement($this->quest));
        $requirements->add(new ValueObjects\LevelRequirement(75));

        $quest = new Entities\Quest(
            guid: 'awesome-quest-id',
            name: 'Another Awesome Quest',
            requirements: $requirements
        );

        foreach ($quest->getRequirements() as $requirement) {
            if (!$requirement->pass($this->player)) {
                $this->assertTrue(true);
                return;
            }
        }

        $this->fail('Expected at least one requirement to fail.');
    }

    #[Test]
    public function pass_when_player_does_meet_requirements(): void
    {
        $requirements = new ValueObjects\QuestRequirements();
        $requirements->add(new ValueObjects\QuestRequirement($this->quest));
        $requirements->add(new ValueObjects\LevelRequirement(65));

        $quest = new Entities\Quest(
            guid: 'awesome-quest-id',
            name: 'A Dark Knight Rises',
            requirements: $requirements
        );

        foreach ($quest->getRequirements() as $requirement) {
            if (!$requirement->pass($this->player)) {
                $this->fail('A requirement failed when it should have passed.');
            }
        }

        $this->assertTrue(true);
    }
}
