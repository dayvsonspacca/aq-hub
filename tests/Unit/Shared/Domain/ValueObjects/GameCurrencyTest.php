<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Shared\Domain\ValueObjects;

use AqHub\Shared\Domain\Enums\CurrencyType;
use AqHub\Shared\Domain\ValueObjects\GameCurrency;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class GameCurrencyTest extends TestCase
{
    #[Test]
    public function should_create_game_currency_instance_and_stores_it_data()
    {
        $value = 100;
        $type  = CurrencyType::AdventureCoins;

        $gameCurrency = GameCurrency::create($value, $type)->unwrap();

        $this->assertInstanceOf(GameCurrency::class, $gameCurrency);
        $this->assertSame($value, $gameCurrency->getValue());
        $this->assertSame($type, $gameCurrency->getType());
    }

    #[Test]
    public function should_fail_because_price_value_is_negative()
    {
        $value = -100;
        $type  = CurrencyType::AdventureCoins;

        $result = GameCurrency::create($value, $type);

        $this->assertNotInstanceOf(GameCurrency::class, $result->getData());
        $this->assertNull($result->getData());
        $this->assertTrue($result->isError());
        $this->assertSame($result->getMessage(), 'The currency value cant be negative.');
    }
}
