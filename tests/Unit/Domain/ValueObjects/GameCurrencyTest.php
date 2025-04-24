<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObjects;

use AqWiki\Domain\{ValueObjects, Exceptions, Enums};
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

final class GameCurrencyTest extends TestCase
{
    private const int NEGATIVE_PRICE = -10;
    private const int POSITIVE_PRICE = 10;

    #[Test]
    public function should_fail_because_price_value_is_negative_from_start()
    {
        $this->expectException(Exceptions\InvalidGameCurrencyException::class);
        $this->expectExceptionMessage("The price of an item can't be negative.");

        new ValueObjects\GameCurrency(self::NEGATIVE_PRICE, Enums\CurrencyType::AdventureCoins);
    }

    #[Test]
    public function should_fail_when_price_change_to_negative()
    {
        $this->expectException(Exceptions\InvalidGameCurrencyException::class);
        $this->expectExceptionMessage("The price of an item can't be negative.");

        $price = new ValueObjects\GameCurrency(self::POSITIVE_PRICE, Enums\CurrencyType::AdventureCoins);

        $price->changeValue(self::NEGATIVE_PRICE);
    }

    #[Test]
    public function should_can_change_currency_type()
    {
        $price = new ValueObjects\GameCurrency(self::POSITIVE_PRICE, Enums\CurrencyType::AdventureCoins);
        $price->changeType(Enums\CurrencyType::Coins);

        $this->assertSame(Enums\CurrencyType::Coins, $price->getType());
    }

    #[Test]
    public function should_change_value()
    {
        $price = new ValueObjects\GameCurrency(self::POSITIVE_PRICE, Enums\CurrencyType::AdventureCoins);
        $price->changeValue(50);

        $this->assertSame(50, $price->getValue());
    }
}
