<?php

declare(strict_types=1);

namespace Tests\Unit\Quests\Domain\ValueObjects;

use AqHub\Player\Domain\ValueObjects\{PlayerInventory, Name};
use AqHub\Quests\Domain\ValueObjects\LevelRequirement;
use AqHub\Shared\Domain\ValueObjects\Identifier;
use AqHub\Player\Domain\Entities\Player;
use PHPUnit\Framework\Attributes\Test;
use AqHub\Tests\Unit\TestCase;

final class LevelRequirementTest extends TestCase
{
    #[Test]
    public function should_create_level_requirement_instance_and_stores_it_data()
    {
        $level            = 5;
        $levelRequirement = new LevelRequirement($level);

        $this->assertInstanceOf(LevelRequirement::class, $levelRequirement);
        $this->assertSame($level, $levelRequirement->getLevel());
    }

    #[Test]
    public function should_pass_when_player_meet_level_requirement()
    {
        $id        = Identifier::create(1)->unwrap();
        $level     = 100;
        $inventory = $this->createMock(PlayerInventory::class);
        $name      = Name::create('Hilise')->unwrap();

        $player = Player::create(
            $id,
            $name,
            $level,
            $inventory
        )->unwrap();

        $level            = 5;
        $levelRequirement = new LevelRequirement($level);

        $this->assertTrue($levelRequirement->pass($player));
    }

    public function should_fail_when_player_does_not_meet_level_requirement()
    {
        $id        = Identifier::create(1)->unwrap();
        $level     = 10;
        $inventory = $this->createMock(PlayerInventory::class);
        $name      = Name::create('Hilise')->unwrap();

        $player = Player::create(
            $id,
            $name,
            $level,
            $inventory
        )->unwrap();

        $level            = 50;
        $levelRequirement = new LevelRequirement($level);

        $this->assertFalse($levelRequirement->pass($player));
    }
}
