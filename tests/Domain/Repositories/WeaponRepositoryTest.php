<?php

declare(strict_types=1);

namespace Tests\Domain\ValueObjects;

use AqWiki\Domain\{Repositories, Entities};
use AqWiki\Infrastructure\Repositories\Fakes\FakeWeaponRepository;
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\TestCase;

final class WeaponRepositoryTest extends TestCase
{
    private Repositories\WeaponRepositoryInterface $weaponRepository;

    protected function setUp(): void
    {
        $this->weaponRepository = new FakeWeaponRepository();
    }

    #[Test]
    public function should_returns_a_weapon()
    {
        $weapon = $this->weaponRepository->getById('necrotic-sword-of-doom');

        $this->assertInstanceOf(Entities\Weapon::class, $weapon);
    }

    #[Test]
    public function should_returns_null_when_not_found()
    {
        $weapon = $this->weaponRepository->getById('blade-of-awe');

        $this->assertNull($weapon);
    }
}
