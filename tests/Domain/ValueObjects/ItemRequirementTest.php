<?php

declare(strict_types=1);

namespace Tests\Domain\ValueObjects;

use AqWiki\Domain\{ValueObjects, Entities, Enums, Abstractions};
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\TestCase;

final class ItemRequirementTest extends TestCase
{
    public const ITEM_NAME        = 'Abyssal Angel Naval Commander';
    public const ITEM_DESCRIPTION = 'Abyssal Angel Commanders sail the Celestial Seas, and are feared across the realms for the cutthroat way they have of taking their opponents down.';

    private Abstractions\AqwItem $item;

    protected function setUp(): void
    {
        $this->item = new class (
            self::ITEM_NAME,
            Enums\ItemRarity::RareRarity,
            new ValueObjects\GameCurrency(0, Enums\CurrencyType::AdventureCoins),
            new ValueObjects\GameCurrency(0, Enums\CurrencyType::AdventureCoins),
            self::ITEM_DESCRIPTION
        ) extends Abstractions\AqwItem {};
    }

    #[Test]
    public function should_fail_when_player_dont_have_item()
    {
        $itemRequirement = new ValueObjects\ItemRequirement($this->item);

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
        $itemRequirement = new ValueObjects\ItemRequirement($this->item);

        $this->assertSame(true, $itemRequirement->pass(
            new Entities\Player(
                80,
                [$this->item]
            )
        ));
    }
}
