<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Domain\Enums;

use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class ItemRarityTest extends TestCase
{
    #[Test]
    public function should_result_in_success_to_all_corrects_rarities()
    {
        $correctTypes = [
            'weird Rarity', 'weird', 'rare rarity', 'rare',
            'epic rarity', 'epic', 'legendary item rarity', 'legendary rarity',
            'legendary', 'awesome rarity', 'awesome', 'seasonal rare rarity',
            'seasonal item rarity', 'seasonal', 'artifact rarity', 'artifact',
            'boss drop rarity', 'boss drop', 'impossible rarity', 'impossible',
            '1% drop rarity', '1% drop', 'unknown rarity', 'unknown',
            'secret Rarity', 'secret'
        ];

        foreach ($correctTypes as $type) {
            $result = ItemRarity::fromString($type);
            $this->assertTrue($result->isSuccess());
            $this->assertInstanceOf(ItemRarity::class, $result->getData());
        }
    }

    #[Test]
    public function should_result_in_error_when_invalid_rarity()
    {
        $invalidRarity = 'weird weird rarity';

        $result = ItemRarity::fromString($invalidRarity);
        $this->assertTrue($result->isError());
        $this->assertSame('Rarity not defined: weird weird rarity', $result->getMessage());
    }

    #[Test]
    public function should_return_the_valid_string_version()
    {
        $rarities = [
            ItemRarity::Weird, ItemRarity::Rare, ItemRarity::Epic, ItemRarity::Legendary,
            ItemRarity::Awesome, ItemRarity::Seasonal, ItemRarity::Artifact, ItemRarity::BossDrop,
            ItemRarity::Impossible, ItemRarity::OnePercentDrop, ItemRarity::Unknown, ItemRarity::Secret
        ];

        $correctString = [
            'Weird', 'Rare', 'Epic', 'Legendary',
            'Awesome', 'Seasonal', 'Artifact', 'Boss Drop',
            'Impossible', '1% Drop', 'Unknown', 'Secret'
        ];

        foreach ($rarities as $index => $rarity) {
            $result = $rarity->toString();
            $this->assertEquals($correctString[$index], $result);
        }
    }
}
