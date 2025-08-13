<?php

declare(strict_types=1);

namespace AqWiki\Shared\Domain\ValueObjects;

use AqWiki\Shared\Domain\Enums\CurrencyType;

class GameCurrency
{
    private function __construct(
        private readonly int $value,
        private CurrencyType $type
    ) {
    }

    /** @return Result<GameCurrency> */
    public static function create(int $value, CurrencyType $type)
    {
        if ($value < 0) {
            return Result::error('The currency value cant be negative.', null);
        }

        return Result::success(null, new self($value, $type));
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
