<?php

declare(strict_types=1);

namespace Tests\Domain\ValueObjects;

use AqWiki\Infrastructure\Repositories\FakeWeaponRepository;
use AqWiki\Domain\{Entities, Exceptions};
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\TestCase;

final class WeaponTest extends TestCase
{
    private Entities\Weapon $weapon;

    protected function setUp(): void
    {
        $weaponRepository = new FakeWeaponRepository();

        $this->weapon = $weaponRepository->getById('necrotic-sword-of-doom');
    }

    #[Test]
    public function should_change_base_damage()
    {
        $this->weapon->changeBaseDamage('30-36');
        $this->assertSame('30-36', $this->weapon->getBaseDamage());
    }

    #[Test]
    public function should_fail_because_base_damage_is_empty()
    {
        $this->expectException(Exceptions\InvalidItemAttributeException::class);
        $this->expectExceptionMessage('The weapon base damage can not be empty.');

        $this->weapon->changeBaseDamage('');
    }

    #[Test]
    public function should_fail_because_invalid_base_damage()
    {
        $this->expectException(Exceptions\InvalidItemAttributeException::class);
        $this->expectExceptionMessage('The weapon base damage needs to be in pattern `min-max`.');

        $this->weapon->changeBaseDamage('30');
    }
}
