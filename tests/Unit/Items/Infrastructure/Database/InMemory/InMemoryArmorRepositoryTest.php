<?php

declare(strict_types=1);

namespace Tests\Unit\Items\Domain\ValueObjects;

use AqHub\Items\Domain\Entities\Armor;
use AqHub\Items\Domain\Repositories\Data\ArmorData;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Items\Infrastructure\Repositories\InMemory\InMemoryArmorRepository;
use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Shared\Domain\ValueObjects\StringIdentifier;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class InMemoryArmorRepositoryTest extends TestCase
{
    #[Test]
    public function should_persist_an_armor()
    {
        $repository = new InMemoryArmorRepository();

        $name        = 'ArchFiend DoomLord';
        $description = "Not even the dark magic of the Shadowscythe is enough to satisfy this soul's lust for power. This item does 35% more damage to any monster, and gives 25% more class points, XP, gold, and rep when equipped.";
        $tags        = new ItemTags([TagType::AdventureCoins]);

        $itemInfo = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags)->unwrap();

        $result = $repository->persist($itemInfo);

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(ArmorData::class, $result->unwrap());
        $this->assertSame('339be7deafb1fcc932663d92b6f0ea2db2960c9d73cbb64432129cfdd64dfd98', $result->unwrap()->identifier->getValue());
    }

    #[Test]
    public function should_fail_when_persist_an_armor_with_same_identifier()
    {
        $repository = new InMemoryArmorRepository();

        $name        = 'ArchFiend DoomLord';
        $description = "Not even the dark magic of the Shadowscythe is enough to satisfy this soul's lust for power. This item does 35% more damage to any monster, and gives 25% more class points, XP, gold, and rep when equipped.";
        $tags        = new ItemTags([TagType::AdventureCoins]);

        $itemInfo = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags)->unwrap();

        $repository->persist($itemInfo);
        $result = $repository->persist($itemInfo);

        $this->assertTrue($result->isError());
        $this->assertSame(null, $result->getData());
        $this->assertSame('An Armor with same identifier already exists: 339be7deafb1fcc932663d92b6f0ea2db2960c9d73cbb64432129cfdd64dfd98', $result->getMessage());
    }

    #[Test]
    public function should_find_armor_by_identifier()
    {
        $repository = new InMemoryArmorRepository();

        $name        = 'ArchFiend DoomLord';
        $description = "Not even the dark magic of the Shadowscythe is enough to satisfy this soul's lust for power. This item does 35% more damage to any monster, and gives 25% more class points, XP, gold, and rep when equipped.";
        $tags        = new ItemTags([TagType::AdventureCoins]);
        $itemInfo    = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags)->unwrap();

        $armor = $repository->persist($itemInfo)->unwrap();

        $result = $repository->findByIdentifier(ItemIdentifierGenerator::generate($itemInfo, Armor::class)->unwrap());

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(ArmorData::class, $result->unwrap());
        $this->assertSame($name, $result->unwrap()->name->value);
    }

    #[Test]
    public function should_return_null_when_armor_not_found_by_identifier()
    {
        $repository = new InMemoryArmorRepository();

        $name = 'ArchFiend DoomLord';
        $id   = StringIdentifier::create($name)->unwrap();

        $result = $repository->findByIdentifier($id);

        $this->assertTrue($result->isError());
        $this->assertSame(null, $result->getData());
    }
}
