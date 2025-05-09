<?php

declare(strict_types=1);

namespace AqWiki\Items\Domain\ValueObjects;

use AqWiki\Shared\Domain\ValueObjects\GameCurrency;

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
