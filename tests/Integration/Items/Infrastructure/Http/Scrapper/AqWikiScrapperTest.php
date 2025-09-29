<?php

declare(strict_types=1);

namespace Tests\Integration\Items\Infrastructure\Http\Scrapper;

use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\ValueObjects\Description;
use AqHub\Items\Domain\ValueObjects\ItemTags;
use AqHub\Items\Domain\ValueObjects\Name;
use AqHub\Items\Infrastructure\Http\Scrappers\AqWikiScrapper;
use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class AqWikiScrapperTest extends TestCase
{
    #[Test]
    public function should_scrapper_item_data()
    {
        $name = Name::create('Cape of Awe')->unwrap();

        $result = AqWikiScrapper::findItemData($name);
        $itemData = $result->getData();

        $this->assertTrue($result->isSuccess());

        $this->assertSame($itemData->name, $name);
        $this->assertEquals($itemData->description->value, Description::create('An AWE-some cape for the truly awesome. Gives 25% more rep, gold, XP and class points when equipped.')->unwrap()->value);
        $this->assertEquals($itemData->rarity, ItemRarity::Awesome);
        $this->assertEquals($itemData->tags, new ItemTags([TagType::AdventureCoins]));
    }
}
