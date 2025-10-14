<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Domain\Entities;

use AqHub\Items\Domain\Entities\Helmet;
use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class HelmetTest extends TestCase
{
    #[Test]
    public function should_create_helmet_instance_and_stores_it_data()
    {
        $name        = 'Auroran Cryomagus Ponytail';
        $description = 'I forgot the description';
        $tags        = new ItemTags([TagType::AdventureCoins]);
        $itemInfo    = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags, ItemRarity::Epic)->unwrap();
        $id          = ItemIdentifierGenerator::generate($itemInfo, Helmet::class)->unwrap();

        $helmet = Helmet::create(
            $id,
            $itemInfo,
        )->unwrap();

        $this->assertInstanceOf(Helmet::class, $helmet);
        $this->assertSame($id->getValue(), $helmet->getId());
        $this->assertSame($name, $helmet->getName());
        $this->assertSame($description, $helmet->getDescription());
        $this->assertSame($tags, $helmet->getTags());
    }
}
