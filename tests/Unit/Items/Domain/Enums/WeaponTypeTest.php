<?php

declare(strict_types=1);

namespace Tests\Unit\Items\Domain\Enums;

use AqHub\Items\Domain\Enums\WeaponType;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class WeaponTypeTest extends TestCase
{
    #[Test]
    public function should_result_in_success_to_all_corrects_weapon_types()
    {
        $correctTypes = [
            'Axe', 'Bow', 'Dagger', 'Gauntlet',
            'Gun', 'Mace', 'Polearm', 'Staff',
            'Sword', 'Wand', 'Whip'
        ];

        foreach ($correctTypes as $type) {
            $result = WeaponType::fromString($type);
            $this->assertTrue($result->isSuccess());
            $this->assertInstanceOf(WeaponType::class, $result->getData());
        }
    }

    #[Test]
    public function should_result_in_error_when_invalid_weapon_type()
    {
        $invalidType = 'hyper sword';

        $result = WeaponType::fromString($invalidType);
        $this->assertTrue($result->isError());
        $this->assertSame('Type not defined: hyper sword', $result->getMessage());
    }

    #[Test]
    public function should_return_the_valid_string_version()
    {
        $weaponTypes = [
            WeaponType::Axe, WeaponType::Bow, WeaponType::Dagger, WeaponType::Gauntlet,
            WeaponType::Gun, WeaponType::Mace, WeaponType::Polearm, WeaponType::Staff,
            WeaponType::Sword, WeaponType::Wand, WeaponType::Whip
        ];

        $correctString = [
            'Axe', 'Bow', 'Dagger', 'Gauntlet',
            'Gun', 'Mace', 'Polearm', 'Staff',
            'Sword', 'Wand', 'Whip'
        ];

        foreach ($weaponTypes as $index => $type) {
            $result = $type->toString();
            $this->assertEquals($correctString[$index], $result);
        }
    }
}
