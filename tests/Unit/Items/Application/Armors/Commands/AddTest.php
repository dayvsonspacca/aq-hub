<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Application\Armors\Queries;

use AqHub\Core\Infrastructure\Cache\FileCache;
use AqHub\Core\Result;
use AqHub\Items\Application\Armors\Commands\Add;
use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Domain\ValueObjects\{ItemInfo};
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

    #[Test]
    public function should_call_add_command_and_invalidate_tags()
    {
        $result = Result::success('Armor saved successfully.', null);

        $this->repositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(ItemInfo::class))
            ->willReturn($result);

        $this->cacheMock
            ->expects($this->once())
            ->method('invalidateTags')
            ->with(['new-armor'])
            ->willReturn(true);

        $armorData = (new ArmorDataProvider())->build();
        $itemInfo  = ItemInfo::create($armorData->name, $armorData->description, $armorData->tags, $armorData->rarity)->unwrap();

        $this->addCommand->execute($itemInfo);
    }
}
