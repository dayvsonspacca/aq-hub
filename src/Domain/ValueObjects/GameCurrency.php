<?php

declare(strict_types=1);

namespace AqWiki\Domain\ValueObjects;

use AqWiki\Domain\{Exceptions, Enums};

final class GameCurrency
{
    private int $value;
    private Enums\CurrencyType $type;

    public function __construct(int $value, Enums\CurrencyType $type)
    {
        $this->changeType($type);
        $this->changeValue($value);
    }

    public function changeValue(int $value): self
    {
        if ($value < 0) {
            throw Exceptions\GameCurrencyException::negativePrice();
        }

        $this->value = $value;
        return $this;
    }

    public function changeType(Enums\CurrencyType $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getType(): Enums\CurrencyType
    {
        return $this->type;
    }
}
