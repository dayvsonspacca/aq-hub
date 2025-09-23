<?php

declare(strict_types=1);

namespace Tests\Unit\Items\Domain\ValueObjects;

use AqWiki\Items\Domain\ValueObjects\ItemCurrencyInfo;
use AqWiki\Shared\Domain\ValueObjects\GameCurrency;
use AqWiki\Shared\Domain\Enums\CurrencyType;
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

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
