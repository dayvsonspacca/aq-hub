<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Domain\ValueObjects;

use AqHub\Items\Domain\ValueObjects\ItemCurrencyInfo;
use AqHub\Shared\Domain\Enums\CurrencyType;
use AqHub\Shared\Domain\ValueObjects\GameCurrency;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class ItemCurrencyInfoTest extends TestCase
{
    #[Test]
    public function should_create_item_game_currency_info_instance_and_stores_it_data()
    {
        $sellback = GameCurrency::create(100, CurrencyType::AdventureCoins)->unwrap();
        $price    = GameCurrency::create(0, CurrencyType::Gold)->unwrap();

        $currencyInfo = new ItemCurrencyInfo($sellback, $price);

        $this->assertInstanceOf(ItemCurrencyInfo::class, $currencyInfo);
        $this->assertSame($sellback, $currencyInfo->getSellback());
        $this->assertSame($price, $currencyInfo->getPrice());
    }
}
