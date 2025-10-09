<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Env;

use AqHub\Shared\Domain\ValueObjects\Result;

enum AppMode
{
    case Development;
    case Production;

    /**
     * @return Result<AppMode>
     */
    public static function fromString(string $mode): Result
    {
        return match ($mode) {
            'dev' => Result::success(null, self::Development),
            'prod' => Result::success(null, self::Production),
            default => Result::error('Mode not defined: ' . $mode, null),
        };
    }
}