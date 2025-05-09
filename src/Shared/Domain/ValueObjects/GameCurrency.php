<?php

declare(strict_types=1);

namespace AqWiki\Shared\Domain\ValueObjects;

use AqWiki\Shared\Domain\Enums\{ResultStatus, CurrencyType};

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
            return new Result(ResultStatus::Error, 'The currency value cant be negative.', null);
        }

        return new Result(ResultStatus::Success, null, new self($value, $type));
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
