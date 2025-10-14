<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Enums;

use AqHub\Shared\Domain\Contracts\{FromString, ToString};
use AqHub\Shared\Domain\ValueObjects\Result;
use InvalidArgumentException;

enum ItemRarity implements FromString, ToString
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
    public static function fromString(string $string): Result
    {
        $rarity = mb_strtolower($string);

        try {
            return match ($rarity) {
                'weird rarity', 'weird' => Result::success(null, self::Weird),
                'rare rarity', 'rare' => Result::success(null, self::Rare),
                'epic rarity', 'epic' => Result::success(null, self::Epic),
                'legendary item rarity', 'legendary rarity', 'legendary' => Result::success(null, self::Legendary),
                'awesome rarity', 'awesome' => Result::success(null, self::Awesome),
                'seasonal rare rarity', 'seasonal item rarity', 'seasonal rarity', 'seasonal' => Result::success(null, self::Seasonal),
                'artifact rarity', 'artifact' => Result::success(null, self::Artifact),
                'boss drop rarity', 'boss drop' => Result::success(null, self::BossDrop),
                'impossible rarity', 'impossible' => Result::success(null, self::Impossible),
                '1% drop rarity', '1% drop' => Result::success(null, self::OnePercentDrop),
                'unknown rarity', 'unknown' => Result::success(null, self::Unknown),
                'secret rarity', 'secret' => Result::success(null, self::Secret),
                default => throw new InvalidArgumentException('Rarity not defined: ' . $rarity)
            };
        } catch (\Throwable $e) {
            return Result::error($e->getMessage(), null);
        }
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
