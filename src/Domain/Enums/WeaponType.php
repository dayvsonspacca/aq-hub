<?php

declare(strict_types=1);

namespace AqWiki\Domain\Enums;

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

    public function toString(): string
    {
        return match($this) {
            self::Axe      => 'Axe',
            self::Bow      => 'Bow',
            self::Dagger   => 'Dagger',
            self::Gauntlet => 'Gauntlet',
            self::Gun      => 'Gun',
            self::Mace     => 'Mace',
            self::Polearm  => 'Polearm',
            self::Staff    => 'Staff',
            self::Sword    => 'Sword',
            self::Wand     => 'Wand',
            self::Whip     => 'Whip',
        };
    }
}
