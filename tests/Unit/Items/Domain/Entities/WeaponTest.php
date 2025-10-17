<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Domain\Entities;

use AqHub\Items\Domain\Entities\Weapon;
use AqHub\Items\Domain\Enums\{ItemRarity, WeaponType};
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Shared\Domain\Enums\ItemTag;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class WeaponTest extends TestCase
{
    #[Test]
    public function should_create_weapon_instance_and_stores_it_data()
    {
        $name        = 'Necrotic Sword of Doom';
        $description = 'The darkness compels… DOOOOOOOOOOOM!!!';
        $tags        = new ItemTags([ItemTag::AdventureCoins]);
        $type        = WeaponType::Sword;
        $itemInfo    = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags, ItemRarity::Legendary)->unwrap();
        $id          = ItemIdentifierGenerator::generate($itemInfo, Weapon::class)->unwrap();

        $weapon = Weapon::create(
            $id,
            $itemInfo,
            $type
        )->unwrap();

        $this->assertInstanceOf(Weapon::class, $weapon);
        $this->assertSame($id->getValue(), $weapon->getId());
        $this->assertSame($name, $weapon->getName());
        $this->assertSame($description, $weapon->getDescription());
        $this->assertSame($tags, $weapon->getTags());
        $this->assertSame($type, $weapon->getType());
    }
}
