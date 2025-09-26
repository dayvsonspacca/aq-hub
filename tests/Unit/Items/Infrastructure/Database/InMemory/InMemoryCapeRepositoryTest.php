<?php

declare(strict_types=1);

namespace Tests\Unit\Items\Domain\ValueObjects;

use AqHub\Items\Domain\Entities\Cape;
use AqHub\Items\Domain\Repositories\Data\CapeData;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Items\Infrastructure\Repositories\InMemory\InMemoryCapeRepository;
use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Shared\Domain\ValueObjects\StringIdentifier;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class InMemoryCapeRepositoryTest extends TestCase
{
    #[Test]
    public function should_persist_an_cape()
    {
        $repository = new InMemoryCapeRepository();

        $name        = 'Cape of Awe';
        $description = 'An AWE-some cape for the truly awesome. Gives 25% more rep, gold, XP and class points when equipped';
        $tags        = new ItemTags([TagType::AdventureCoins]);

        $itemInfo = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags)->unwrap();

        $result = $repository->persist($itemInfo);

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(CapeData::class, $result->unwrap());
        // $this->assertSame('5258530f3d77c94ba139eb404e3bd5f18bb1925c04d0d6dc9db6f3d37048dff7', $result->unwrap()->getId());
    }

    #[Test]
    public function should_fail_when_persist_an_cape_with_same_identifier()
    {
        $repository = new InMemoryCapeRepository();

        $name        = 'Cape of Awe';
        $description = 'An AWE-some cape for the truly awesome. Gives 25% more rep, gold, XP and class points when equipped';
        $tags        = new ItemTags([TagType::AdventureCoins]);

        $itemInfo = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags)->unwrap();

        $repository->persist($itemInfo);
        $result = $repository->persist($itemInfo);

        $this->assertTrue($result->isError());
        $this->assertSame(null, $result->getData());
        $this->assertSame('A Cape with same identifier already exists: 5258530f3d77c94ba139eb404e3bd5f18bb1925c04d0d6dc9db6f3d37048dff7', $result->getMessage());
    }

    #[Test]
    public function should_find_cape_by_identifier()
    {
        $repository = new InMemoryCapeRepository();

        $name        = 'Cape of Awe';
        $description = 'An AWE-some cape for the truly awesome. Gives 25% more rep, gold, XP and class points when equipped';
        $tags        = new ItemTags([TagType::AdventureCoins]);
        $itemInfo    = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags)->unwrap();

        $cape = $repository->persist($itemInfo)->unwrap();

        $result = $repository->findByIdentifier(ItemIdentifierGenerator::generate($itemInfo, Cape::class)->unwrap());

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(CapeData::class, $result->unwrap());
        $this->assertSame($name, $result->unwrap()->name->value);
    }

    #[Test]
    public function should_return_null_when_cape_not_found_by_identifier()
    {
        $repository = new InMemoryCapeRepository();

        $name = 'Cape of Awe';
        $id   = StringIdentifier::create($name)->unwrap();

        $result = $repository->findByIdentifier($id);

        $this->assertTrue($result->isError());
        $this->assertSame(null, $result->getData());
    }
}
