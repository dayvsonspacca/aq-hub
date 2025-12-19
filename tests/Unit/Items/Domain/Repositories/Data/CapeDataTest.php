<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Domain\Repositories\Data;

use AqHub\Items\Domain\Entities\Armor;
use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\Repositories\Data\CapeData;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Shared\Domain\Enums\ItemTag;
use AqHub\Tests\TestCase;
use DateTime;
use PHPUnit\Framework\Attributes\Test;

final class CapeDataTest extends TestCase
{
    #[Test]
    public function should_create_cape_data_instance_and_stores_it_data()
    {
        $name        = Name::create('Cape of Awe')->unwrap();
        $description = Description::create('An AWE-some cape for the truly awesome. Gives 25% more rep, gold, XP and class points when equipped.')->unwrap();
        $tags        = new ItemTags([ItemTag::AdventureCoins]);
        $rarity      = ItemRarity::Awesome;

        $itemInfo      = ItemInfo::create($name, $description, $tags, $rarity)->unwrap();
        $id            = ItemIdentifierGenerator::generate($itemInfo, Armor::class)->unwrap();
        $registeredAt  = new DateTime('2025-10-14');
        $canAccessBank = false;

        $capeData = new CapeData(
            $id,
            $name,
            $description,
            $tags,
            $registeredAt,
            $rarity,
            $canAccessBank
        );

        $this->assertInstanceOf(CapeData::class, $capeData);
        $this->assertSame($id, $capeData->identifier);
        $this->assertSame($name, $capeData->name);
        $this->assertSame($description, $capeData->description);
        $this->assertSame($tags, $capeData->tags);
        $this->assertSame($registeredAt, $capeData->registeredAt);
        $this->assertSame($rarity, $capeData->rarity);
        $this->assertSame($canAccessBank, $capeData->canAccessBank);
    }

    #[Test]
    public function should_return_correct_array_format_on_to_array()
    {
        $name        = Name::create('Cape of Awe')->unwrap();
        $description = Description::create('An AWE-some cape for the truly awesome. Gives 25% more rep, gold, XP and class points when equipped.')->unwrap();
        $tags        = new ItemTags([ItemTag::AdventureCoins]);
        $rarity      = ItemRarity::Awesome;

        $itemInfo      = ItemInfo::create($name, $description, $tags, $rarity)->unwrap();
        $id            = ItemIdentifierGenerator::generate($itemInfo, Armor::class)->unwrap();
        $registeredAt  = new DateTime('2025-10-14');
        $canAccessBank = false;

        $capeData = new CapeData(
            $id,
            $name,
            $description,
            $tags,
            $registeredAt,
            $rarity,
            $canAccessBank
        );

        $this->assertSame($capeData->toArray(), [
            'id' => $id->getValue(),
            'name' => $name->value,
            'description' => $description->value,
            'registered_at' => $registeredAt->format('Y-m-d H:i:s'),
            'rarity' => $rarity->toString(),
            'tags' => $tags->toArray(),
            'can_access_bank' => false
        ]);
    }
}
