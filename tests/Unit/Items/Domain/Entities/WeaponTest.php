<?php

declare(strict_types=1);

namespace Tests\Unit\Items\Domain\Entities;

use AqWiki\Items\Domain\ValueObjects\{ItemTags, ItemInfo};
use AqWiki\Shared\Domain\ValueObjects\Identifier;
use AqWiki\Items\Domain\Enums\WeaponType;
use AqWiki\Items\Domain\Entities\Weapon;
use AqWiki\Shared\Domain\Enums\TagType;
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

final class WeaponTest extends TestCase
{
    #[Test]
    public function should_create_weapon_instance_and_stores_it_data()
    {
        $id = Identifier::create(1)->getData();
        $name = 'Necrotic Sword of Doom';
        $description = 'The darkness compels… DOOOOOOOOOOOM!!!';
        $tags = new ItemTags([TagType::AdventureCoins]);
        $type = WeaponType::Sword;

        $itemInfo = ItemInfo::create($name, $description, $tags)->unwrap();

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
