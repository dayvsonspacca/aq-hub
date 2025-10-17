<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Quests\Domain\ValueObjects;

use AqHub\Player\Domain\Entities\Player;
use AqHub\Player\Domain\ValueObjects\{Level, Name, PlayerInventory};
use AqHub\Quests\Domain\ValueObjects\LevelRequirement;
use AqHub\Shared\Domain\ValueObjects\IntIdentifier;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

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

        $level            = 5;
        $levelRequirement = new LevelRequirement($level);

        $this->assertTrue($levelRequirement->pass($player));
    }

    public function should_fail_when_player_does_not_meet_level_requirement()
    {
        $id        = IntIdentifier::create(1)->unwrap();
        $level     = Level::create(10)->unwrap();
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
