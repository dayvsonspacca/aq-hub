<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\ValueObjects;

use AqHub\Shared\Domain\ValueObjects\GameCurrency;

class ItemCurrencyInfo
{
    public function __construct(
        private GameCurrency $sellback,
        private GameCurrency $price
    ) {
    }

    public function getSellback(): GameCurrency
    {
        return $this->sellback;
    }

    public function getPrice(): GameCurrency
    {
        return $this->price;
    }
}
