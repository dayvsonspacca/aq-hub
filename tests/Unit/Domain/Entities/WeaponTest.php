<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObjects;

use AqWiki\Domain\{Entities, Exceptions};
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

final class WeaponTest extends TestCase
{
    private Entities\Weapon $weapon;

    protected function setUp(): void
    {
        $this->weapon = new Entities\Weapon();
    }

    #[Test]
    public function should_change_base_damage()
    {
        $this->weapon->defineBaseDamage('30-36');
        $this->assertSame('30-36', $this->weapon->getBaseDamage());
    }

    #[Test]
    public function should_fail_because_base_damage_is_empty()
    {
        $this->expectException(Exceptions\AqwItemException::class);
        $this->expectExceptionMessage('The weapon base damage can not be empty.');

        $this->weapon->defineBaseDamage('');
    }

    #[Test]
    public function should_fail_because_invalid_base_damage()
    {
        $this->expectException(Exceptions\AqwItemException::class);
        $this->expectExceptionMessage('The weapon base damage needs to be in pattern `min-max`.');

        $this->weapon->defineBaseDamage('30');
    }
}
