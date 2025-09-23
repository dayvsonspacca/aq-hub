<?php

declare(strict_types=1);

namespace AqWiki\Items\Domain\ValueObjects;

use AqWiki\Shared\Domain\ValueObjects\Result;

class Name
{
    private function __construct(public readonly string $value) {}

    /**
     * @return Result<Name|null>
     */
    public static function create(string $name): Result
    {
        $name = trim($name);

        if (empty($name)) {
            return Result::error('The name of an item cant be empty.', null);
        }

        return Result::success(null, new self($name));
    }
}
