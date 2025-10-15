<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Domain\Services;

use AqHub\Items\Domain\Entities\{Armor, Weapon};
use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Shared\Domain\Enums\ItemTag;
use AqHub\Shared\Domain\ValueObjects\StringIdentifier;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class ItemIdentifierGeneratorTest extends TestCase
{
    #[Test]
    public function should_generate_same_identifier_for_same_item(): void
    {
        $tags  = new ItemTags([ItemTag::AdventureCoins, ItemTag::Legend]);
        $item1 = ItemInfo::create(Name::create('Burning Blade')->unwrap(), Description::create('A fiery sword')->unwrap(), $tags, ItemRarity::BossDrop)->unwrap();
        $item2 = ItemInfo::create(Name::create('Burning Blade')->unwrap(), Description::create('A fiery sword')->unwrap(), $tags, ItemRarity::BossDrop)->unwrap();

        $result1 = ItemIdentifierGenerator::generate($item1, Weapon::class);
        $result2 = ItemIdentifierGenerator::generate($item2, Weapon::class);

        $this->assertTrue($result1->isSuccess());
        $this->assertTrue($result2->isSuccess());
        $this->assertInstanceOf(StringIdentifier::class, $result1->getData());
        $this->assertSame($result1->getData()->getValue(), $result2->getData()->getValue());
    }

    #[Test]
    public function should_generate_different_identifier_for_different_items(): void
    {
        $tags1 = new ItemTags([ItemTag::AdventureCoins]);
        $tags2 = new ItemTags([ItemTag::Legend]);

        $item1 = ItemInfo::create(Name::create('Burning Blade')->unwrap(), Description::create('A fiery sword')->unwrap(), $tags1, ItemRarity::BossDrop)->unwrap();
        $item2 = ItemInfo::create(Name::create('Burning Blade')->unwrap(), Description::create('A fiery sword')->unwrap(), $tags2, ItemRarity::BossDrop)->unwrap();

        $result1 = ItemIdentifierGenerator::generate($item1, Weapon::class);
        $result2 = ItemIdentifierGenerator::generate($item2, Weapon::class);

        $this->assertTrue($result1->isSuccess());
        $this->assertTrue($result2->isSuccess());
        $this->assertNotSame($result1->getData()->getValue(), $result2->getData()->getValue());
    }

    #[Test]
    public function should_generate_different_identifier_for_different_class_names(): void
    {
        $tags = new ItemTags([ItemTag::AdventureCoins, ItemTag::Legend]);
        $item = ItemInfo::create(Name::create('Burning Blade')->unwrap(), Description::create('A fiery sword')->unwrap(), $tags, ItemRarity::BossDrop)->unwrap();

        $resultWeapon = ItemIdentifierGenerator::generate($item, Weapon::class);
        $resultArmor  = ItemIdentifierGenerator::generate($item, Armor::class);

        $this->assertTrue($resultWeapon->isSuccess());
        $this->assertTrue($resultArmor->isSuccess());
        $this->assertNotSame($resultWeapon->getData()->getValue(), $resultArmor->getData()->getValue());
    }
}
