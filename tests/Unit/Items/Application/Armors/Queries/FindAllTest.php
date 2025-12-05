<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Application\Armors\Queries;

use AqHub\Core\Infrastructure\Cache\FileCache;
use AqHub\Items\Application\Armors\Queries\FindAll;
use AqHub\Items\Application\Armors\Queries\Outputs\FindAllOutput;
use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Domain\Repositories\Filters\ArmorFilter;
use AqHub\Tests\DataProviders\ArmorDataProvider;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;

class FindAllTest extends TestCase
{
    private MockObject&ArmorRepository $repositoryMock;
    private MockObject&FileCache $cacheMock;

    private FindAll $findAllQuery;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = $this->createMock(ArmorRepository::class);
        $this->cacheMock      = $this->createMock(FileCache::class);

        $this->findAllQuery = new FindAll($this->repositoryMock, $this->cacheMock);
    }

    #[Test]
    public function should_create_find_all_query()
    {
        $this->assertInstanceOf(FindAll::class, $this->findAllQuery);
    }

    #[Test]
    public function should_call_cache_with_correct_parameters_and_callback()
    {
        $filter = $this->createMock(ArmorFilter::class);
        $filter->method('generateUniqueKey')->willReturn('mock-cache-key');

        $expectedArmors = ArmorDataProvider::make()->buildCollection(3);

        $this->cacheMock
            ->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('mock-cache-key'),
                $this->isCallable('callable'),
                $this->isNull(),
                $this->equalTo(['new-armor'])
            )
            ->willReturnCallback(
                fn ($key, $callback, $expiresAfter, $tags) => $callback()
            );

        $this->repositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedArmors);

        $expectedTotal = 5;

        $this->repositoryMock
            ->expects($this->once())
            ->method('countAll')
            ->willReturn($expectedTotal);

        $output = $this->findAllQuery->execute($filter);

        $this->assertInstanceOf(FindAllOutput::class, $output);

        $this->assertSame($expectedArmors, $output->armors);
        $this->assertSame($expectedTotal, $output->total);
    }
}
