<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\ValueObjects;

use AqHub\Core\Result;

class Description
{
    private function __construct(public readonly string $value)
    {
    }

    /**
     * @return Result<Description|null>
     */
    public static function create(string $description): Result
    {
        $description = trim($description);

        if (empty($description)) {
            return Result::error('The description of an item cant be empty.', null);
        }

        return Result::success(null, new self($description));
    }
}
