<?php

declare(strict_types=1);

namespace AqHub\Player\Domain\ValueObjects;

use AqHub\Core\Result;

class Name
{
    private function __construct(public readonly string $value)
    {
    }

    /**
     * @return Result<Name|null>
     */
    public static function create(string $name): Result
    {
        $name = mb_strtoupper(trim($name));

        if (empty($name)) {
            return Result::error('The name of a player cant be empty.', null);
        }

        return Result::success(null, new self($name));
    }
}
