<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entities;

use AqWiki\Domain\{ValueObjects, Entities, Enums};
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

final class WeaponTest extends TestCase
{
    private Entities\Weapon $weapon;
    private string $weaponId = 'awesome-weapon-id';
    private string $name = 'ShadowReaper of Doom';
    private string $description = 'A cursed blade with unimaginable power.';
    private string $baseDamage = '45-60';
    private Enums\WeaponType $type;
    private ValueObjects\GameCurrency $price;
    private ValueObjects\GameCurrency $sellback;
    private ValueObjects\ItemTags $tags;

    protected function setUp(): void
    {
        parent::setUp();

        $this->price = $this->createMock(ValueObjects\GameCurrency::class);
        $this->sellback = $this->createMock(ValueObjects\GameCurrency::class);
        $this->tags = $this->createMock(ValueObjects\ItemTags::class);
        $this->type = Enums\WeaponType::Sword;

        $this->weapon = new Entities\Weapon(
            $this->weaponId,
            $this->name,
            $this->description,
            $this->price,
            $this->sellback,
            $this->tags,
            $this->baseDamage,
            $this->type
        );
    }

    #[Test]
    public function it_stores_and_returns_the_weapon_id(): void
    {
        $this->assertSame($this->weaponId, $this->weapon->getGuid());
    }

    #[Test]
    public function it_stores_and_returns_the_name(): void
    {
        $this->assertSame($this->name, $this->weapon->getName());
    }

    #[Test]
    public function it_stores_and_returns_the_description(): void
    {
        $this->assertSame($this->description, $this->weapon->getDescription());
    }

    #[Test]
    public function it_stores_and_returns_the_price(): void
    {
        $this->assertSame($this->price, $this->weapon->getPrice());
    }

    #[Test]
    public function it_stores_and_returns_the_sellback(): void
    {
        $this->assertSame($this->sellback, $this->weapon->getSellback());
    }

    #[Test]
    public function it_stores_and_returns_the_tags(): void
    {
        $this->assertSame($this->tags, $this->weapon->getTags());
    }

    #[Test]
    public function it_stores_and_returns_the_base_damage(): void
    {
        $this->assertSame($this->baseDamage, $this->weapon->getBaseDamage());
    }

    #[Test]
    public function it_stores_and_returns_the_weapon_type(): void
    {
        $this->assertSame($this->type, $this->weapon->getType());
    }
}
