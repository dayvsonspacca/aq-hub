<?php

declare(strict_types=1);

namespace Tests\Domain\ValueObjects;

use AqWiki\Infrastructure\Repositories\FakeWeaponRepository;
use AqWiki\Domain\{Entities, Exceptions, Repositories};
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\TestCase;

final class InventoryTest extends TestCase
{
    private Entities\Inventory $inventory;
    private Repositories\WeaponRepositoryInterface $weaponRepository;

    protected function setUp(): void
    {
        $this->inventory = new Entities\Inventory();
        $this->weaponRepository = new FakeWeaponRepository();
    }

    #[Test]
    public function should_can_add_a_item()
    {
        $this->assertSame(0, $this->inventory->count());
        $this->inventory->addItem($this->weaponRepository->getById('necrotic-sword-of-doom'));
        $this->assertSame(1, $this->inventory->count());
    }

    #[Test]
    public function fails_when_add_same_item()
    {
        $this->expectException(Exceptions\InventoryException::class);
        $this->expectExceptionMessage('An inventory can not have more than one instance of item.');

        $this->inventory->addItem($this->weaponRepository->getById('necrotic-sword-of-doom'));
        $this->inventory->addItem($this->weaponRepository->getById('necrotic-sword-of-doom'));
    }

    #[Test]
    public function fails_when_add_more_items_than_space()
    {
        $this->expectException(Exceptions\InventoryException::class);
        $this->expectExceptionMessage("There's no space avaliable.");

        $this->inventory->defineSpaces(0);

        $this->inventory->addItem($this->weaponRepository->getById('necrotic-sword-of-doom'));
    }

    #[Test]
    public function fails_when_spaces_is_negative()
    {
        $this->expectException(Exceptions\InventoryException::class);
        $this->expectExceptionMessage('An inventory can not have negative spaces.');

        $this->inventory->defineSpaces(-10);
    }
}
