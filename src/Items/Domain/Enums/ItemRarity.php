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

    /**
     * @return Result<ItemRarity>
     */
    public static function fromString(string $rarity): Result
    {
        return match ($rarity) {
            'Weird Rarity', 'Weird' => Result::success(null, self::Weird),
            'Rare Rarity', 'Rare' => Result::success(null, self::Rare),
            'Epic Rarity', 'Epic' => Result::success(null, self::Epic),
            'Legendary Item Rarity', 'Legendary Rarity', 'Legendary' => Result::success(null, self::Legendary),
            'Awesome Rarity', 'Awesome' => Result::success(null, self::Awesome),
            'Seasonal Rare Rarity', 'Seasonal Item Rarity', 'Seasonal Rarity', 'Seasonal' => Result::success(null, self::Seasonal),
            'Artifact Rarity', 'Artifact' => Result::success(null, self::Artifact),
            'Boss Drop Rarity', 'Boss Drop' => Result::success(null, self::BossDrop),
            'Impossible Rarity', 'Impossible' => Result::success(null, self::Impossible),
            '1% Drop Rarity', '1% Drop' => Result::success(null, self::OnePercentDrop),
            'Unknown Rarity', 'Unknown' => Result::success(null, self::Unknown),
            'Secret Rarity', 'Secret' => Result::success(null, self::Secret),
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
