<?php

declare(strict_types=1);

namespace Tests\Unit\Items\Domain\ValueObjects;

use AqHub\Items\Domain\Entities\Helmet;
use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\Repositories\Data\HelmetData;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Items\Infrastructure\Repositories\InMemory\InMemoryHelmetRepository;
use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Shared\Domain\ValueObjects\StringIdentifier;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class InMemoryHelmetRepositoryTest extends TestCase
{
    #[Test]
    public function should_persist_an_helmet()
    {
        $repository = new InMemoryHelmetRepository();

        $name        = 'Helm of Awe';
        $description = 'Gilded and golden this AWE-some helm shows true Awe spirt! Gives 25% more rep, gold, XP, and class points when equipped.';
        $tags        = new ItemTags([TagType::AdventureCoins]);

        $itemInfo = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags, ItemRarity::Awesome)->unwrap();

        $result = $repository->persist($itemInfo);

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(HelmetData::class, $result->unwrap());
        $this->assertSame('27122db97bf21df2817797a7721fa5b0afe3a64e8c7382c1cd5a68a4127651df', $result->unwrap()->identifier->getValue());
    }

    #[Test]
    public function should_fail_when_persist_an_helmet_with_same_identifier()
    {
        $repository = new InMemoryHelmetRepository();

        $name        = 'Helm of Awe';
        $description = 'Gilded and golden this AWE-some helm shows true Awe spirt! Gives 25% more rep, gold, XP, and class points when equipped.';
        $tags        = new ItemTags([TagType::AdventureCoins]);

        $itemInfo = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags, ItemRarity::Awesome)->unwrap();

        $repository->persist($itemInfo);
        $result = $repository->persist($itemInfo);

        $this->assertTrue($result->isError());
        $this->assertSame(null, $result->getData());
        $this->assertSame('A Helmet with same identifier already exists: 27122db97bf21df2817797a7721fa5b0afe3a64e8c7382c1cd5a68a4127651df', $result->getMessage());
    }

    #[Test]
    public function should_find_helmet_by_identifier()
    {
        $repository = new InMemoryHelmetRepository();

        $name        = 'Helm of Awe';
        $description = 'Gilded and golden this AWE-some helm shows true Awe spirt! Gives 25% more rep, gold, XP, and class points when equipped.';
        $tags        = new ItemTags([TagType::AdventureCoins]);
        $itemInfo    = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags, ItemRarity::Awesome)->unwrap();

        $helmet = $repository->persist($itemInfo)->unwrap();

        $result = $repository->findByIdentifier(ItemIdentifierGenerator::generate($itemInfo, Helmet::class)->unwrap());

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(HelmetData::class, $result->unwrap());
        $this->assertSame($name, $result->unwrap()->name->value);
    }

    #[Test]
    public function should_return_null_when_helmet_not_found_by_identifier()
    {
        $repository = new InMemoryHelmetRepository();

        $name = 'Helm of Awe';
        $id   = StringIdentifier::create($name)->unwrap();

        $result = $repository->findByIdentifier($id);

        $this->assertTrue($result->isError());
        $this->assertSame(null, $result->getData());
    }
}
