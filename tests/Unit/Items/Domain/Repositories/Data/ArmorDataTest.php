<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Domain\Repositories\Data;

use AqHub\Items\Domain\Entities\Armor;
use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\Repositories\Data\ArmorData;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Tests\Unit\TestCase;
use DateTime;
use PHPUnit\Framework\Attributes\Test;

final class ArmorDataTest extends TestCase
{
    #[Test]
    public function should_create_armor_data_instance_and_stores_it_data()
    {
        $name        = Name::create('ArchFiend DoomLord')->unwrap();
        $description = Description::create("Not even the dark magic of the Shadowscythe is enough to satisfy this soul's lust for power.")->unwrap();
        $tags        = new ItemTags([TagType::AdventureCoins]);
        $rarity      = ItemRarity::Epic;

        $itemInfo     = ItemInfo::create($name, $description, $tags, $rarity)->unwrap();
        $id           = ItemIdentifierGenerator::generate($itemInfo, Armor::class)->unwrap();
        $registeredAt = new DateTime('2025-10-14');

        $armorData = new ArmorData(
            $id,
            $name,
            $description,
            $tags,
            $registeredAt,
            $rarity
        );

        $this->assertInstanceOf(ArmorData::class, $armorData);
        $this->assertSame($id, $armorData->identifier);
        $this->assertSame($name, $armorData->name);
        $this->assertSame($description, $armorData->description);
        $this->assertSame($tags, $armorData->tags);
        $this->assertSame($registeredAt, $armorData->registeredAt);
        $this->assertSame($rarity, $armorData->rarity);
    }

    #[Test]
    public function should_return_correct_array_format_on_to_array()
    {
        $name        = Name::create('ArchFiend DoomLord')->unwrap();
        $description = Description::create("Not even the dark magic of the Shadowscythe is enough to satisfy this soul's lust for power.")->unwrap();
        $tags        = new ItemTags([TagType::AdventureCoins]);
        $rarity      = ItemRarity::Epic;

        $itemInfo     = ItemInfo::create($name, $description, $tags, $rarity)->unwrap();
        $id           = ItemIdentifierGenerator::generate($itemInfo, Armor::class)->unwrap();
        $registeredAt = new DateTime('2025-10-14');

        $armorData = new ArmorData(
            $id,
            $name,
            $description,
            $tags,
            $registeredAt,
            $rarity
        );

        $this->assertSame($armorData->toArray(), [
            'id' => $id->getValue(),
            'name' => $name->value,
            'description' => $description->value,
            'registered_at' => $registeredAt->format('Y-m-d H:i:s'),
            'rarity' => $rarity->toString(),
            'tags' => $tags->toArray()
        ]);
    }
}
