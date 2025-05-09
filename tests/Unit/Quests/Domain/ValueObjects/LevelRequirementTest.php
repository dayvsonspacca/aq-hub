<?php

declare(strict_types=1);

namespace Tests\Unit\Quests\Domain\ValueObjects;

use AqWiki\Quests\Domain\ValueObjects\LevelRequirement;
use AqWiki\Player\Domain\ValueObjects\PlayerInventory;
use AqWiki\Player\Domain\Entities\Player;
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

final class LevelRequirementTest extends TestCase
{
    #[Test]
    public function should_create_level_requirement_instance_and_stores_it_data()
    {
        $level = 5;
        $levelRequirement = new LevelRequirement($level);

        $this->assertInstanceOf(LevelRequirement::class, $levelRequirement);
        $this->assertSame($level, $levelRequirement->getLevel());
    }

    #[Test]
    public function should_pass_when_player_meet_level_requirement()
    {
        $guid = 'Hilise';
        $level = 100;
        $inventory = $this->createMock(PlayerInventory::class);

        $player = Player::create(
            $guid,
            $level,
            $inventory
        )->unwrap();

        $level = 5;
        $levelRequirement = new LevelRequirement($level);

        $this->assertTrue($levelRequirement->pass($player));
    }

    public function should_fail_when_player_does_not_meet_level_requirement()
    {
        $guid = 'Hilise';
        $level = 10;
        $inventory = $this->createMock(PlayerInventory::class);

        $player = Player::create(
            $guid,
            $level,
            $inventory
        )->unwrap();

        $level = 50;
        $levelRequirement = new LevelRequirement($level);

        $this->assertFalse($levelRequirement->pass($player));
    }
}
