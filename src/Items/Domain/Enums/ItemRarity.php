<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Enums;

use AqHub\Shared\Domain\ValueObjects\Result;

enum ItemRarity
{
    case Weird;
    case Rare;
    case Epic;
    case Legendary;
    case Awesome;
    case Seasonal;
    case Artifact;
    case BossDrop;
    case Impossible;
    case OnePercentDrop;
    case Unknown;
    case Secret;

    public static function fromString(string $rarity): Result
    {
        return match (strtolower($rarity)) {
            'weird' => Result::success(null, self::Weird),
            'rare' => Result::success(null, self::Rare),
            'epic' => Result::success(null, self::Epic),
            'legendary' => Result::success(null, self::Legendary),
            'awesome' => Result::success(null, self::Awesome),
            'seasonal' => Result::success(null, self::Seasonal),
            'artifact' => Result::success(null, self::Artifact),
            'boss drop' => Result::success(null, self::BossDrop),
            'impossible' => Result::success(null, self::Impossible),
            'one percent drop' => Result::success(null, self::OnePercentDrop),
            'unknown' => Result::success(null, self::Unknown),
            'secret' => Result::success(null, self::Secret),
            default => Result::error('Rarity not defined: ' . $rarity, null),
        };
    }

    public function toString(): string
    {
        return match ($this) {
            self::Weird => 'Weird',
            self::Rare => 'Rare',
            self::Epic => 'Epic',
            self::Legendary => 'Legendary',
            self::Awesome => 'Awesome',
            self::Seasonal => 'Seasonal',
            self::Artifact => 'Artifact',
            self::BossDrop => 'Boss Drop',
            self::Impossible => 'Impossible',
            self::OnePercentDrop => '1% Drop',
            self::Unknown => 'Unknown',
            self::Secret => 'Secret',
        };
    }
}
