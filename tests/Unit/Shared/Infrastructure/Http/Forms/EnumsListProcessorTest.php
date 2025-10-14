<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Shared\Infrastructure\Http\Forms;

use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Shared\Infrastructure\Http\Forms\EnumListProcessor;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class EnumsListProcessorTest extends TestCase
{
    #[Test]
    public function it_converts_comma_separated_rarities_string_to_item_rarity_enum_array_and_ignores_invalid_values()
    {
        $rarities = 'weird,rare,epic';
        $result = EnumListProcessor::fromComma($rarities, ItemRarity::class);

        $this->assertCount(3, $result);
        $this->assertSame($result, [
            ItemRarity::Epic,
            ItemRarity::Rare,
            ItemRarity::Weird
        ]);
    }

    #[Test]
    public function it_converts_comma_separated_tags_string_to_tag_type_enum_array_and_ignores_invalid_values()
    {
        $rarities = 'legend,ac';
        $result = EnumListProcessor::fromComma($rarities, TagType::class);

        $this->assertCount(2, $result);
        $this->assertSame($result, [
            TagType::AdventureCoins,
            TagType::Legend
        ]);
    }
}