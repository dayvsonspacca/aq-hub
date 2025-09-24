<?php

declare(strict_types=1);

namespace Tests\Unit\Quests\Domain\ValueObjects;

use AqHub\Items\Domain\Entities\{Armor, Weapon};
use AqHub\Player\Domain\Entities\Player;
use AqHub\Player\Domain\ValueObjects\{Level, Name, PlayerInventory};
use AqHub\Quests\Domain\ValueObjects\ItemRequirement;
use AqHub\Shared\Domain\ValueObjects\IntIdentifier;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class ItemRequirementTest extends TestCase
{
    #[Test]
    public function should_create_item_requirement_instance_and_stores_it_data()
    {
        $weapon          = $this->createMock(Weapon::class);
        $itemRequirement = new ItemRequirement($weapon);

        $this->assertInstanceOf(ItemRequirement::class, $itemRequirement);
        $this->assertSame($weapon, $itemRequirement->getItem());
    }

    #[Test]
    public function should_pass_when_player_meet_item_requirement()
    {
        $weapon    = $this->createMock(Weapon::class);
        $id        = IntIdentifier::create(1)->unwrap();
        $level     = Level::create(100)->unwrap();
        $inventory = new PlayerInventory([$weapon], 10);
        $name      = Name::create('Hilise')->unwrap();

        $player = Player::create(
            $id,
            $name,
            $level,
            $inventory
        )->unwrap();

        $itemRequirement = new ItemRequirement($weapon);

        $this->assertTrue($itemRequirement->pass($player));
    }

    #[Test]
    public function should_fail_when_player_does_not_meet_item_requirement()
    {
        $weapon    = $this->createMock(Weapon::class);
        $id        = IntIdentifier::create(1)->unwrap();
        $level     = Level::create(100)->unwrap();
        $inventory = new PlayerInventory([$weapon], 10);
        $name      = Name::create('Hilise')->unwrap();

        $player = Player::create(
            $id,
            $name,
            $level,
            $inventory
        )->unwrap();

        $itemRequirement = new ItemRequirement($this->createMock(Armor::class));

        $this->assertFalse($itemRequirement->pass($player));
    }
}
