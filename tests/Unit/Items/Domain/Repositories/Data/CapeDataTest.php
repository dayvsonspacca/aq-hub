<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Domain\Repositories\Data;

use AqHub\Items\Domain\Entities\Cape;
use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\Repositories\Data\CapeData;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Tests\Unit\TestCase;
use DateTime;
use PHPUnit\Framework\Attributes\Test;

final class CapeDataTest extends TestCase
{
    #[Test]
    public function should_create_cape_data_instance_and_stores_it_data()
    {
        $name          = Name::create("Hollowborn Trainer's Companion Bank")->unwrap();
        $description   = Description::create('No mortal shall resist the shadows, not even the cutest of the moglins. Great help you have there now, Trainer.')->unwrap();
        $tags          = new ItemTags([]);
        $rarity        = ItemRarity::Legendary;
        $canAccessBank = true;

        $itemInfo     = ItemInfo::create($name, $description, $tags, $rarity)->unwrap();
        $id           = ItemIdentifierGenerator::generate($itemInfo, Cape::class)->unwrap();
        $registeredAt = new DateTime('2025-10-14');

        $capeData = new CapeData(
            $id,
            $name,
            $description,
            $tags,
            $canAccessBank,
            $registeredAt,
            $rarity
        );

        $this->assertInstanceOf(CapeData::class, $capeData);
        $this->assertSame($id, $capeData->identifier);
        $this->assertSame($name, $capeData->name);
        $this->assertSame($description, $capeData->description);
        $this->assertSame($tags, $capeData->tags);
        $this->assertSame($registeredAt, $capeData->registeredAt);
        $this->assertSame($rarity, $capeData->rarity);
    }

    #[Test]
    public function should_return_correct_array_format_on_to_array()
    {
        $name          = Name::create("Hollowborn Trainer's Companion Bank")->unwrap();
        $description   = Description::create('No mortal shall resist the shadows, not even the cutest of the moglins. Great help you have there now, Trainer.')->unwrap();
        $tags          = new ItemTags([]);
        $rarity        = ItemRarity::Legendary;
        $canAccessBank = true;

        $itemInfo     = ItemInfo::create($name, $description, $tags, $rarity)->unwrap();
        $id           = ItemIdentifierGenerator::generate($itemInfo, Cape::class)->unwrap();
        $registeredAt = new DateTime('2025-10-14');

        $capeDatas = new CapeData(
            $id,
            $name,
            $description,
            $tags,
            $canAccessBank,
            $registeredAt,
            $rarity
        );

        $this->assertSame($capeDatas->toArray(), [
             'id' => $id->getValue(),
            'name' => $name->value,
            'description' => $description->value,
            'registered_at' => $registeredAt->format('Y-m-d H:i:s'),
            'rarity' => $rarity->toString(),
            'can_access_bank' => $canAccessBank,
            'tags' => $tags->toArray()
        ]);
    }
}
