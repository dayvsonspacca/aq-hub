<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Shared\Domain\Enums;

use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class TagTypeTest extends TestCase
{
    #[Test]
    public function should_result_in_success_to_all_corrects_tags()
    {
        $correctTypes = ['Legend', 'adventure coins', 'ac', 'rare', 'pseudo rare', 'pseudo', 'seasonal', 'special offer', 'special'];

        foreach ($correctTypes as $type) {
            $result = TagType::fromString($type);
            $this->assertTrue($result->isSuccess());
            $this->assertInstanceOf(TagType::class, $result->getData());
        }
    }

    #[Test]
    public function should_result_in_error_when_invalid_type()
    {
        $invalidType = 'adventure c';

        $result = TagType::fromString($invalidType);
        $this->assertTrue($result->isError());
        $this->assertSame('Tag not defined: adventure c', $result->getMessage());
    }

    #[Test]
    public function should_return_the_valid_string_version()
    {
        $tagTypes      = [TagType::Legend, TagType::AdventureCoins, TagType::Rare, TagType::PseudoRare, TagType::Seasonal, TagType::SpecialOffer];
        $correctString = ['Legend', 'Adventure Coins', 'Rare', 'Pseudo Rare', 'Seasonal', 'Special Offer'];

        foreach ($tagTypes as $index => $tag) {
            $result = $tag->toString();
            $this->assertEquals($correctString[$index], $result);
        }
    }
}
