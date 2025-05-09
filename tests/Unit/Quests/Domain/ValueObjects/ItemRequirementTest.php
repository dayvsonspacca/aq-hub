<?php

declare(strict_types=1);

namespace Tests\Unit\Quests\Domain\ValueObjects;

use AqWiki\Player\Domain\ValueObjects\PlayerInventory;
use AqWiki\Quests\Domain\ValueObjects\ItemRequirement;
use AqWiki\Items\Domain\Entities\{Weapon, Armor};
use AqWiki\Player\Domain\Entities\Player;
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

final class ItemRequirementTest extends TestCase
{
    #[Test]
    public function should_create_item_requirement_instance_and_stores_it_data()
    {
        $weapon = $this->createMock(Weapon::class);
        $itemRequirement = new ItemRequirement($weapon);

        $this->assertInstanceOf(ItemRequirement::class, $itemRequirement);
        $this->assertSame($weapon, $itemRequirement->getItem());
    }

    #[Test]
    public function should_pass_when_player_meet_item_requirement()
    {
        $weapon = $this->createMock(Weapon::class);
        $guid = 'Hilise';
        $level = 100;
        $inventory = new PlayerInventory([$weapon], 10);

        $player = Player::create(
            $guid,
            $level,
            $inventory
        )->unwrap();

        $itemRequirement = new ItemRequirement($weapon);

        $this->assertTrue($itemRequirement->pass($player));
    }

    #[Test]
    public function should_fail_when_player_does_not_meet_item_requirement()
    {
        $weapon = $this->createMock(Weapon::class);
        $guid = 'Hilise';
        $level = 100;
        $inventory = new PlayerInventory([$weapon], 10);

        $player = Player::create(
            $guid,
            $level,
            $inventory
        )->unwrap();

        $itemRequirement = new ItemRequirement($this->createMock(Armor::class));

        $this->assertFalse($itemRequirement->pass($player));
    }
}
