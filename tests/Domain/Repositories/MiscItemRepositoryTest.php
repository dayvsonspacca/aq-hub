<?php

declare(strict_types=1);

namespace Tests\Domain\ValueObjects;

use AqWiki\Domain\{Repositories, Entities, ValueObjects, Enums, Exceptions};
use AqWiki\Infrastructure\Repositories\Fakes\FakeMiscItemsRepository;
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\TestCase;

final class MiscItemRepositoryTest extends TestCase
{
    private Repositories\MiscItemRepositoryInterface $miscItemRepository;

    protected function setUp(): void
    {
        $this->miscItemRepository = new FakeMiscItemsRepository();
    }

    #[Test]
    public function should_return_item_when_found()
    {
        $this->assertInstanceOf(Entities\MiscItem::class, $this->miscItemRepository->findByName('Dark Crystal Shard'));
    }

    #[Test]
    public function should_return_null_when_item_not_found()
    {
        $this->assertNull($this->miscItemRepository->findByName('Abyssal Star'));
    }

    #[Test]
    public function should_persist_an_item()
    {
        $this->assertNull($this->miscItemRepository->findByName('Abyssal Star'));
        $miscItem = new Entities\MiscItem();
        $miscItem
            ->defineName('Abyssal Star')
            ->defineSellback(new ValueObjects\GameCurrency(0, Enums\CurrencyType::AdventureCoins))
            ->defineDescription(
                'This fragile looking gem Nulgath has handed to you feels like it has a never ending pit inside waiting for you to fall in if you stare at it for too long'
            );

        $this->miscItemRepository->persist($miscItem);
        $this->assertInstanceOf(Entities\MiscItem::class, $this->miscItemRepository->findByName('Abyssal Star'));
    }

    #[Test]
    public function fails_when_persist_same_item()
    {
        $this->expectException(Exceptions\RepositoryException::class);
        $this->expectExceptionMessage('An record with same id already exists, repository: ' . $this->miscItemRepository::class);

        $miscItem = new Entities\MiscItem();
        $miscItem
            ->defineName('Dark Crystal Shard')
            ->defineSellback(new ValueObjects\GameCurrency(0, Enums\CurrencyType::AdventureCoins))
            ->defineDescription(
                'A small, refined shard imbued with dark magic. Significantly more valuable than a Tainted Gem.'
            );

        $this->miscItemRepository->persist($miscItem);
    }
}
