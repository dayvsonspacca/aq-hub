<?php

declare(strict_types=1);

namespace Tests\Unit\Items\Domain\Entities;

use AqHub\Items\Domain\ValueObjects\{Description, ItemTags, ItemInfo, Name};
use AqHub\Shared\Domain\ValueObjects\Identifier;
use AqHub\Items\Domain\Entities\Armor;
use AqHub\Shared\Domain\Enums\TagType;
use PHPUnit\Framework\Attributes\Test;
use AqHub\Tests\Unit\TestCase;

final class ArmorTest extends TestCase
{
    #[Test]
    public function should_create_armor_instance_and_stores_it_data()
    {
        $id          = Identifier::create(1)->getData();
        $name        = 'ArchFiend DoomLord';
        $description = "Not even the dark magic of the Shadowscythe is enough to satisfy this soul's lust for power.";
        $tags        = new ItemTags([TagType::AdventureCoins]);

        $itemInfo = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags)->unwrap();

        $armor = Armor::create(
            $id,
            $itemInfo,
        )->unwrap();

        $this->assertInstanceOf(Armor::class, $armor);
        $this->assertSame($id->getValue(), $armor->getId());
        $this->assertSame($name, $armor->getName());
        $this->assertSame($description, $armor->getDescription());
        $this->assertSame($tags, $armor->getTags());
    }
}
