<?php

declare(strict_types=1);

namespace AqWiki\Domain\Exceptions;

use Exception;

final class InvalidGameCurrencyException extends Exception
{
    public static function negativePrice(): self
    {
        return new self("The price of an item can't be negative.");
    }
}
