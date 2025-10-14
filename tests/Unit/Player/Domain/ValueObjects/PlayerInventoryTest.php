<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Player\Domain\ValueObjects;

use AqHub\Items\Domain\Entities\{Armor, Weapon};
use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Player\Domain\ValueObjects\PlayerInventory;
use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

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
    public function should_fail_when_add_same_item_in_player_inventory()
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

    #[Test]
    public function should_can_iterate_on_player_inventory()
    {
        $maxSpaces = 2;
        $items     = [$this->createMock(Weapon::class), $this->createMock(Armor::class)];

        $playerInventory = new PlayerInventory($items, $maxSpaces);

        foreach ($playerInventory as $index => $item) {
            $this->assertInstanceOf($index === 0 ? Weapon::class : Armor::class, $item);
        }
    }

    #[Test]
    public function should_fail_when_try_delete_item_with_ac_tag()
    {
        $maxSpaces   = 50;
        $name        = 'ArchFiend DoomLord';
        $description = "Not even the dark magic of the Shadowscythe is enough to satisfy this soul's lust for power.";
        $tags        = new ItemTags([TagType::AdventureCoins]);
        $itemInfo    = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags, ItemRarity::Epic)->unwrap();
        $id          = ItemIdentifierGenerator::generate($itemInfo, Armor::class)->unwrap();

        $armor = Armor::create(
            $id,
            $itemInfo,
        )->unwrap();
        $items     = [$armor];

        $playerInventory = new PlayerInventory($items, $maxSpaces);

        $result = $playerInventory->delete($armor);

        $this->assertTrue($result->isError());
        $this->assertTrue($playerInventory->has($armor));
        $this->assertSame($result->getMessage(), 'You cant delete that item, AC tag present.');
    }
}
