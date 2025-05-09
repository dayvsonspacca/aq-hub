<?php

declare(strict_types=1);

namespace Tests\Unit\Quests\Domain\ValueObjects;

use AqWiki\Player\Domain\Entities\Player;
use AqWiki\Player\Domain\ValueObjects\PlayerInventory;
use AqWiki\Quests\Domain\ValueObjects\QuestRequirement;
use AqWiki\Quests\Domain\Entities\Quest;
use AqWiki\Quests\Domain\ValueObjects\LevelRequirement;
use AqWiki\Quests\Domain\ValueObjects\QuestRequirements;
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

final class QuestRequirementTest extends TestCase
{
    #[Test]
    public function should_create_quest_requirement_instance_and_stores_it_data()
    {
        $quest = $this->createMock(Quest::class);
        $questRequirement = new QuestRequirement($quest);

        $this->assertInstanceOf(QuestRequirement::class, $questRequirement);
        $this->assertSame($quest, $questRequirement->getQuest());
    }

    #[Test]
    public function should_pass_when_player_meet_quest_requirement()
    {
        $quest = Quest::create(
            '1',
            'Awesome Quest',
            new QuestRequirements([new LevelRequirement(5)])
        )->unwrap();

        $questRequirement = new QuestRequirement($quest);

        $guid = 'Hilise';
        $level = 100;
        $inventory = $this->createMock(PlayerInventory::class);

        $player = Player::create(
            $guid,
            $level,
            $inventory
        )->unwrap();

        $this->assertTrue($questRequirement->pass($player));
    }

    #[Test]
    public function should_fail_when_player_does_not_meet_quest_requirement()
    {
        $quest = Quest::create(
            '1',
            'Awesome Quest',
            new QuestRequirements([new LevelRequirement(50)])
        )->unwrap();

        $questRequirement = new QuestRequirement($quest);

        $guid = 'Hilise';
        $level = 6;
        $inventory = $this->createMock(PlayerInventory::class);

        $player = Player::create(
            $guid,
            $level,
            $inventory
        )->unwrap();

        $this->assertFalse($questRequirement->pass($player));
    }
}
