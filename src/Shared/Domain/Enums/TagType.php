<?php

declare(strict_types=1);

namespace AqHub\Shared\Domain\Enums;

use AqHub\Shared\Domain\ValueObjects\Result;

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
        return match (strtolower($tag)) {
            'Legend', 'legend' => Result::success(null, self::Legend),
            'Adventure Coins', 'ac' => Result::success(null, self::AdventureCoins),
            'Rare', 'rare' => Result::success(null, self::Rare),
            'Pseudo Rare', 'pseudo' => Result::success(null, self::PseudoRare),
            'Seasonal', 'seasonal' => Result::success(null, self::Seasonal),
            'Special Offer', 'special' => Result::success(null, self::SpecialOffer),
            default => Result::error('Tag not defined: ' . $tag, null)
        };
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
