<?php

declare(strict_types=1);

namespace Tests\Unit\Items\Domain\Entities;

use AqHub\Items\Domain\Entities\Cape;
use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class CapeTest extends TestCase
{
    #[Test]
    public function should_create_cape_instance_and_stores_it_data()
    {
        $name        = 'Auroran Cryomagus Ponytail';
        $description = 'Pink auroras are created during intense solar storms. It sounds scary but that phenomenon is considered a blessing by the Cryomagus and others ice magic practioners.';
        $tags        = new ItemTags([TagType::AdventureCoins, TagType::Rare]);
        $itemInfo    = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags, ItemRarity::Rare)->unwrap();
        $id          = ItemIdentifierGenerator::generate($itemInfo, Cape::class)->unwrap();

        $cape = Cape::create(
            $id,
            $itemInfo,
        )->unwrap();

        $this->assertInstanceOf(Cape::class, $cape);
        $this->assertSame($id->getValue(), $cape->getId());
        $this->assertSame($name, $cape->getName());
        $this->assertSame($description, $cape->getDescription());
        $this->assertSame($tags, $cape->getTags());
    }
}
