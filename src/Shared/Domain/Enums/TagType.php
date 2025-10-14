<?php

declare(strict_types=1);

namespace AqHub\Shared\Domain\Enums;

use AqHub\Shared\Domain\ValueObjects\Result;
use InvalidArgumentException;

enum TagType
{
    case Legend;
    case AdventureCoins;
    case Rare;
    case PseudoRare;
    case Seasonal;
    case SpecialOffer;

    /**
     * @return Result<TagType>
     */
    public static function fromString(string $tag): Result
    {
        $tag = mb_strtolower($tag);

        try {
            return match ($tag) {
                'legend' => Result::success(null, self::Legend),
                'adventure coins', 'ac' => Result::success(null, self::AdventureCoins),
                'rare' => Result::success(null, self::Rare),
                'pseudo rare', 'pseudo' => Result::success(null, self::PseudoRare),
                'seasonal' => Result::success(null, self::Seasonal),
                'special offer', 'special' => Result::success(null, self::SpecialOffer),
                default => throw new InvalidArgumentException('Tag not defined: '. $tag)
            };
        } catch (\Throwable $e) {
            return Result::error($e->getMessage(), null);
        }
    }

    public function toString(): string
    {
        return match ($this) {
            self::Legend => 'Legend',
            self::AdventureCoins => 'Adventure Coins',
            self::Rare => 'Rare',
            self::PseudoRare => 'Pseudo Rare',
            self::Seasonal => 'Seasonal',
            self::SpecialOffer => 'Special Offer',
        };
    }
}
