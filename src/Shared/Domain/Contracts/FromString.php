<?php

declare(strict_types=1);

namespace AqHub\Shared\Domain\Contracts;

use AqHub\Shared\Domain\ValueObjects\Result;

interface FromString
{
    public static function fromString(string $string): Result;
}