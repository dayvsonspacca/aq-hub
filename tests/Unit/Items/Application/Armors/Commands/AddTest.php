<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Application\Armors\Queries;

use AqHub\Core\Infrastructure\Cache\FileCache;
use AqHub\Items\Application\Armors\Commands\Add;
use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Domain\Repositories\Filters\ArmorFilter;
use AqHub\Tests\DataProviders\ArmorDataProvider;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;

class AddTest extends TestCase
{
    private MockObject&ArmorRepository $repositoryMock;
    private MockObject&FileCache $cacheMock;

    private Add $addCommand;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = $this->createMock(ArmorRepository::class);
        $this->cacheMock      = $this->createMock(FileCache::class);

        $this->addCommand = new Add($this->repositoryMock, $this->cacheMock);
    }

    #[Test]
    public function should_create_find_all_query()
    {
        $this->assertInstanceOf(Add::class, $this->addCommand);
    }

    // #[Test]
    // public function should_call_cache_with_correct_parameters_and_callback()
    // {
    //     $filter = $this->createMock(ArmorFilter::class);
    //     $filter->method('generateUniqueKey')->willReturn('mock-cache-key');

    //     $expectedArmors = ArmorDataProvider::make()->buildCollection(3);

    //     $this->cacheMock
    //         ->expects($this->once())
    //         ->method('get')
    //         ->with(
    //             $this->equalTo('mock-cache-key'),
    //             $this->isCallable('callable'),
    //             $this->isNull(),
    //             $this->equalTo(['new-armor'])
    //         )
    //         ->willReturnCallback(
    //             fn ($key, $callback, $expiresAfter, $tags) => $callback()
    //         );

    //     $this->repositoryMock
    //         ->expects($this->once())
    //         ->method('findAll')
    //         ->willReturn($expectedArmors);

    //     $actualArmors = $this->findAllQuery->execute($filter);

    //     $this->assertSame($expectedArmors, $actualArmors);
    // }
}
