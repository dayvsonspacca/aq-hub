<?php

declare(strict_types=1);

namespace AqWiki\Shared\Domain\ValueObjects;

use AqWiki\Shared\Domain\Enums\CurrencyType;

class GameCurrency
{
    public function __construct(
        private int $value,
        private CurrencyType $type
    ) {
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getType(): CurrencyType
    {
        return $this->type;
    }
}
