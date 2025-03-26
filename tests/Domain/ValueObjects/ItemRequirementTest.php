<?php

declare(strict_types=1);

namespace Tests\Domain\ValueObjects;

use AqWiki\Domain\{ValueObjects, Entities, Abstractions, Repositories};
use AqWiki\Infrastructure\Repositories\Fakes\FakeWeaponRepository;
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\TestCase;

final class ItemRequirementTest extends TestCase
{
    private Abstractions\AqwItem $item;
    private Repositories\WeaponRepositoryInterface $weaponRepository;

    protected function setUp(): void
    {
        $this->weaponRepository = new FakeWeaponRepository();
        $this->item = $this->weaponRepository->getById('necrotic-sword-of-doom');
    }

    #[Test]
    public function should_fail_when_player_dont_have_item()
    {
        $itemRequirement = new ValueObjects\ItemRequirement($this->item, 1);

        $this->assertSame(false, $itemRequirement->pass(
            new Entities\Player(
                80,
                []
            )
        ));
    }


    #[Test]
    public function should_pass_when_player_have_item()
    {
        $itemRequirement = new ValueObjects\ItemRequirement($this->item, 1);

        $this->assertSame(true, $itemRequirement->pass(
            new Entities\Player(
                80,
                [$this->item]
            )
        ));
    }
}
