<?php

declare(strict_types=1);

namespace Tests\Unit\Items\Domain\Entities;

use AqHub\Items\Domain\ValueObjects\{Description, ItemTags, ItemInfo, Name};
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\Enums\WeaponType;
use AqHub\Items\Domain\Entities\Weapon;
use AqHub\Shared\Domain\Enums\TagType;
use PHPUnit\Framework\Attributes\Test;
use AqHub\Tests\Unit\TestCase;

final class WeaponTest extends TestCase
{
    #[Test]
    public function should_create_weapon_instance_and_stores_it_data()
    {
        $name        = 'Necrotic Sword of Doom';
        $description = 'The darkness compels… DOOOOOOOOOOOM!!!';
        $tags        = new ItemTags([TagType::AdventureCoins]);
        $type        = WeaponType::Sword;
        $itemInfo    = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags)->unwrap();
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
