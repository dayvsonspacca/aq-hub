<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Application\Armors\Queries;

use AqHub\Items\Application\Armors\Queries\FindAll;
use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Domain\Repositories\Data\ArmorData;
use AqHub\Items\Domain\Repositories\Filters\ArmorFilter;
use AqHub\Tests\DataProviders\ArmorDataProvider;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;

class FindAllTest extends TestCase
{
    private MockObject&ArmorRepository $repositoryMock;

    private FindAll $findAllQuery;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = $this->createMock(ArmorRepository::class);

        $this->findAllQuery = new FindAll($this->repositoryMock);
    }

    #[Test]
    public function should_create_find_all_query()
    {
        $this->assertInstanceOf(FindAll::class, $this->findAllQuery);
    }

    #[Test]
    public function should_call_repository_with_filter_and_return_armors()
    {
        $filter = $this->createMock(ArmorFilter::class);

        $expectedArmors = ArmorDataProvider::make()->buildCollection(3);

        $this->repositoryMock
             ->expects($this->once())
             ->method('findAll')
             ->with($this->equalTo($filter))
             ->willReturn($expectedArmors);

        $actualArmors = $this->findAllQuery->execute($filter);

        $this->assertSame($expectedArmors, $actualArmors);

        $this->assertCount(3, $actualArmors);
        $this->assertInstanceOf(ArmorData::class, $actualArmors[0]);
    }
}
