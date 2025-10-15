<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Shared\Domain\Enums;

use AqHub\Shared\Domain\Enums\ItemTag;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class ItemTagTest extends TestCase
{
    #[Test]
    public function should_result_in_success_to_all_corrects_tags()
    {
        $correctTags = ['Legend', 'adventure coins', 'ac', 'rare', 'pseudo rare', 'pseudo', 'seasonal', 'special offer', 'special'];

        foreach ($correctTags as $tag) {
            $result = ItemTag::fromString($tag);
            $this->assertTrue($result->isSuccess());
            $this->assertInstanceOf(ItemTag::class, $result->getData());
        }
    }

    #[Test]
    public function should_result_in_error_when_invalid_tag()
    {
        $invalidTag = 'adventure c';

        $result = ItemTag::fromString($invalidTag);
        $this->assertTrue($result->isError());
        $this->assertSame('Tag not defined: adventure c', $result->getMessage());
    }

    #[Test]
    public function should_return_the_valid_string_version()
    {
        $itemTags      = [ItemTag::Legend, ItemTag::AdventureCoins, ItemTag::Rare, ItemTag::PseudoRare, ItemTag::Seasonal, ItemTag::SpecialOffer];
        $correctString = ['Legend', 'Adventure Coins', 'Rare', 'Pseudo Rare', 'Seasonal', 'Special Offer'];

        foreach ($itemTags as $index => $tag) {
            $result = $tag->toString();
            $this->assertEquals($correctString[$index], $result);
        }
    }
}
