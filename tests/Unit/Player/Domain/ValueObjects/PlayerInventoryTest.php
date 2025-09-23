<?php

declare(strict_types=1);

namespace Tests\Unit\Player\Domain\ValueObjects;

use AqWiki\Player\Domain\ValueObjects\PlayerInventory;
use AqWiki\Items\Domain\Entities\{Armor, Weapon};
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

final class PlayerInventoryTest extends TestCase
{
    #[Test]
    public function should_create_player_inventory_instance_and_stores_it_data()
    {
        $items     = [];
        $maxSpaces = 50;

        $playerInventory = new PlayerInventory($items, $maxSpaces);

        $this->assertInstanceOf(PlayerInventory::class, $playerInventory);
        $this->assertSame($items, $playerInventory->getItems());
        $this->assertSame($maxSpaces, $playerInventory->getMaxSpaces());
        $this->assertSame(50, $playerInventory->getAvaliableSpaces());
        $this->assertSame(0, $playerInventory->count());
    }

    #[Test]
    public function should_can_add_an_item_to_player_inventory()
    {
        $items     = [];
        $maxSpaces = 50;
        $weapon    = $this->createMock(Weapon::class);

        $playerInventory = new PlayerInventory($items, $maxSpaces);

        $result = $playerInventory->add($weapon);

        $this->assertTrue($result->isSuccess());
        $this->assertNull($result->getData());
        $this->assertSame(1, $playerInventory->count());
        $this->assertTrue($playerInventory->has($weapon));
        $this->assertSame(49, $playerInventory->getAvaliableSpaces());
    }

    #[Test]
    public function should_can_delete_an_item_from_player_inventory()
    {
        $maxSpaces = 50;
        $weapon    = $this->createMock(Weapon::class);
        $items     = [$weapon];

        $playerInventory = new PlayerInventory($items, $maxSpaces);

        $result = $playerInventory->delete($weapon);

        $this->assertTrue($result->isSuccess());
        $this->assertNull($result->getData());
        $this->assertSame(0, $playerInventory->count());
        $this->assertFalse($playerInventory->has($weapon));
        $this->assertSame(50, $playerInventory->getAvaliableSpaces());
    }

    #[Test]
    public function should_fail_when_add_sam_item_in_player_inventory()
    {
        $maxSpaces = 50;
        $weapon    = $this->createMock(Weapon::class);
        $items     = [$weapon];

        $playerInventory = new PlayerInventory($items, $maxSpaces);

        $result = $playerInventory->add($weapon);

        $this->assertTrue($result->isError());
        $this->assertNull($result->getData());
        $this->assertSame(1, $playerInventory->count());
        $this->assertTrue($playerInventory->has($weapon));
        $this->assertSame(49, $playerInventory->getAvaliableSpaces());
        $this->assertSame($result->getMessage(), 'The player inventory already has that item.');
    }

    #[Test]
    public function should_fail_when_there_is_no_space_in_player_inventory()
    {
        $maxSpaces = 1;
        $weapon    = $this->createMock(Weapon::class);
        $items     = [$weapon];

        $playerInventory = new PlayerInventory($items, $maxSpaces);

        $result = $playerInventory->add($this->createMock(Armor::class));

        $this->assertTrue($result->isError());
        $this->assertNull($result->getData());
        $this->assertSame(1, $playerInventory->count());
        $this->assertTrue($playerInventory->has($weapon));
        $this->assertSame(0, $playerInventory->getAvaliableSpaces());
        $this->assertSame($result->getMessage(), 'There is no space avaliable in the player inventory.');
    }
}
