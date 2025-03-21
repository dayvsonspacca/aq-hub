<?php

declare(strict_types=1);

namespace Tests\Domain\ValueObjects;

use AqWiki\Domain\{ValueObjects, Enums, Entities};
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\TestCase;

final class AqwItemTest extends TestCase
{
    public const ITEM_NAME        = 'Abyssal Angel Naval Commander';
    public const ITEM_DESCRIPTION = 'Abyssal Angel Commanders sail the Celestial Seas, and are feared across the realms for the cutthroat way they have of taking their opponents down.';

    private Entities\AqwItem $item;

    protected function setUp(): void
    {
        $this->item = new class (
            self::ITEM_NAME,
            Enums\ItemRarity::RareRarity,
            new ValueObjects\GameCurrency(0, Enums\CurrencyType::AdventureCoins),
            new ValueObjects\GameCurrency(0, Enums\CurrencyType::AdventureCoins),
            self::ITEM_DESCRIPTION
        ) extends Entities\AqwItem {};
    }

    #[Test]
    public function validate_item_with_default_values()
    {
        $this->assertInstanceOf(ValueObjects\ItemTags::class, $this->item->tags);
        $this->assertSame(self::ITEM_NAME, $this->item->name);
        $this->assertSame($this->item->sellback->getValue(), 0);
        $this->assertSame($this->item->sellback->getType(), Enums\CurrencyType::AdventureCoins);
        $this->assertSame($this->item->price->getValue(), 0);
        $this->assertSame($this->item->price->getType(), Enums\CurrencyType::AdventureCoins);
        $this->assertSame($this->item->rarity, Enums\ItemRarity::RareRarity);
    }

    #[Test]
    public function should_change_sellback()
    {
        $this->item->sellback->changeValue(20);

        $this->assertSame(20, $this->item->sellback->getValue());
    }
}
