<?php

declare(strict_types=1);

namespace AqHub\Player\Domain\ValueObjects;

use AqHub\Shared\Domain\ValueObjects\Result;

class Level
{
    public const int MAX = 100;
    public const int MIN = 1;

    private function __construct(public readonly int $value) {}

    /**
     * @return Result<Level|null>
     */
    public static function create(int $level): Result
    {
        if ($level >= self::MIN && $level <= self::MAX) {
            return Result::success(null, new self($level));
        }

        return Result::error('The level of the player needs to be in the range of ' . self::MIN . ' - ' . self::MAX . ' | informed: ' . $level, null);
    }
}
