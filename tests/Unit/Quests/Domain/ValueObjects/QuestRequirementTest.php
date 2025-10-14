<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Quests\Domain\ValueObjects;

use AqHub\Player\Domain\Entities\Player;
use AqHub\Player\Domain\ValueObjects\{Level, Name, PlayerInventory};
use AqHub\Quests\Domain\Entities\Quest;
use AqHub\Quests\Domain\ValueObjects\{LevelRequirement, QuestRequirement, QuestRequirements};
use AqHub\Shared\Domain\ValueObjects\IntIdentifier;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class QuestRequirementTest extends TestCase
{
    #[Test]
    public function should_create_quest_requirement_instance_and_stores_it_data()
    {
        $quest            = $this->createMock(Quest::class);
        $questRequirement = new QuestRequirement($quest);

        $this->assertInstanceOf(QuestRequirement::class, $questRequirement);
        $this->assertSame($quest, $questRequirement->getQuest());
    }

    #[Test]
    public function should_pass_when_player_meet_quest_requirement()
    {
        $quest = Quest::create(
            IntIdentifier::create(1)->getData(),
            'Awesome Quest',
            new QuestRequirements([new LevelRequirement(5)])
        )->unwrap();

        $questRequirement = new QuestRequirement($quest);

        $id        = IntIdentifier::create(1)->unwrap();
        $level     = Level::create(100)->unwrap();
        $inventory = $this->createMock(PlayerInventory::class);
        $name      = Name::create('Hilise')->unwrap();

        $player = Player::create(
            $id,
            $name,
            $level,
            $inventory
        )->unwrap();

        $this->assertTrue($questRequirement->pass($player));
    }

    #[Test]
    public function should_fail_when_player_does_not_meet_quest_requirement()
    {
        $quest = Quest::create(
            IntIdentifier::create(1)->unwrap(),
            'Awesome Quest',
            new QuestRequirements([new LevelRequirement(50)])
        )->unwrap();

        $questRequirement = new QuestRequirement($quest);

        $id        = IntIdentifier::create(1)->unwrap();
        $level     = Level::create(1)->unwrap();
        $inventory = $this->createMock(PlayerInventory::class);
        $name      = Name::create('Hilise')->unwrap();

        $player = Player::create(
            $id,
            $name,
            $level,
            $inventory
        )->unwrap();

        $this->assertFalse($questRequirement->pass($player));
    }
}
