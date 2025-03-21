<?php

declare(strict_types=1);

namespace Tests\Domain\ValueObjects;

use AqWiki\Domain\{ValueObjects, Enums, Entities, Exceptions};
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\TestCase;

final class WeaponTest extends TestCase
{
    private const ITEM_NAME        = 'Necrotic Sword of Doom';
    private const ITEM_DESCRIPTION = 'The darkness compels… DOOOOOOOOOOOM!!!';

    private Entities\Weapon $weapon;

    protected function setUp(): void
    {
        $this->weapon = (new Entities\Weapon(
            self::ITEM_NAME,
            Enums\ItemRarity::LegendaryItemRarity,
            null,
            new ValueObjects\GameCurrency(0, Enums\CurrencyType::AdventureCoins),
            self::ITEM_DESCRIPTION
        ))->changeBaseDamage('27-33');
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
