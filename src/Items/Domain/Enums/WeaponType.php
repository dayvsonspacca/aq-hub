<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Enums;

use AqHub\Core\Interfaces\{FromString, ToString};
use AqHub\Core\Result;
use InvalidArgumentException;

enum WeaponType implements FromString, ToString
{
    case Axe;
    case Bow;
    case Dagger;
    case Gauntlet;
    case Gun;
    case Mace;
    case Polearm;
    case Staff;
    case Sword;
    case Wand;
    case Whip;
    /**
     * @return Result<WeaponType|null>
     */
    public static function fromString(string $string): Result
    {
        $type = mb_strtolower($string);

        try {
            return match ($type) {
                'axe' => Result::success(null, self::Axe),
                'bow' => Result::success(null, self::Bow),
                'dagger' => Result::success(null, self::Dagger),
                'gauntlet' => Result::success(null, self::Gauntlet),
                'gun' => Result::success(null, self::Gun),
                'mace' => Result::success(null, self::Mace),
                'polearm' => Result::success(null, self::Polearm),
                'staff' => Result::success(null, self::Staff),
                'sword' => Result::success(null, self::Sword),
                'wand' => Result::success(null, self::Wand),
                'whip' => Result::success(null, self::Whip),
                default => throw new InvalidArgumentException('Type not defined: ' . $type)
            };
        } catch (\Throwable $e) {
            return Result::error($e->getMessage(), null);
        }
    }

    public function toString(): string
    {
        return match ($this) {
            self::Axe => 'Axe',
            self::Bow => 'Bow',
            self::Dagger => 'Dagger',
            self::Gauntlet => 'Gauntlet',
            self::Gun => 'Gun',
            self::Mace => 'Mace',
            self::Polearm => 'Polearm',
            self::Staff => 'Staff',
            self::Sword => 'Sword',
            self::Wand => 'Wand',
            self::Whip => 'Whip',
        };
    }
}
