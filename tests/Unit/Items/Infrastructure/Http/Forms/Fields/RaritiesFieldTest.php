<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Infrastructure\Http\Forms\Fields;

use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Infrastructure\Http\Forms\Fields\RaritiesField;
use AqHub\Tests\Unit\TestRequests;
use PHPUnit\Framework\Attributes\Test;

final class RaritiesFieldTest extends TestRequests
{
    #[Test]
    public function should_ignore_invalid_rarities_and_filter_by_valid_ones()
    {
        $request = $this->createRequest(['rarities' => 'Unknown,rare rarity,legend rarity']);

        $rarities = RaritiesField::fromRequest($request);

        $this->assertCount(2, $rarities);
        $this->assertSame(array_values($rarities), [ItemRarity::Unknown, ItemRarity::Rare]);
    }
}
