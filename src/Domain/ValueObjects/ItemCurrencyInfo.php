<?php

declare(strict_types=1);

namespace AqWiki\Domain\ValueObjects;

use AqWiki\Domain\{Exceptions, Enums};

class ItemCurrencyInfo
{
    private GameCurrency $sellback;
    private GameCurrency $price;

    public function getSellback(): GameCurrency
    {
        return $this->sellback;
    }

    public function getPrice(): GameCurrency
    {
        return $this->price;
    }
}
