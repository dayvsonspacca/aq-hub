<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Enums;

use AqHub\Shared\Domain\ValueObjects\Result;

enum WeaponType
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
    public static function fromString(string $type): Result
    {
        return match ($type) {
            'Axe' => Result::success(null, self::Axe),
            'Bow' => Result::success(null, self::Bow),
            'Dagger' => Result::success(null, self::Dagger),
            'Gauntlet' => Result::success(null, self::Gauntlet),
            'Gun' => Result::success(null, self::Gun),
            'Mace' => Result::success(null, self::Mace),
            'Polearm' => Result::success(null, self::Polearm),
            'Staff' => Result::success(null, self::Staff),
            'Sword' => Result::success(null, self::Sword),
            'Wand' => Result::success(null, self::Wand),
            'Whip' => Result::success(null, self::Whip),
            default => Result::error('Type not defined: ' . $type, null),
        };
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
