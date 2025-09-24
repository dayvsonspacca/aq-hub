<?php

declare(strict_types=1);

namespace Tests\Unit\Player\Domain\Entities;

use AqHub\Player\Domain\ValueObjects\{PlayerInventory, Name};
use AqHub\Shared\Domain\ValueObjects\Identifier;
use AqHub\Player\Domain\Entities\Player;
use PHPUnit\Framework\Attributes\Test;
use AqHub\Tests\Unit\TestCase;

final class PlayerTest extends TestCase
{
    #[Test]
    public function should_create_player_instance_and_stores_it_data()
    {
        $id        = Identifier::create(1)->unwrap();
        $level     = 100;
        $inventory = $this->createMock(PlayerInventory::class);
        $name      = Name::create('Hilise')->unwrap();

        $player = Player::create(
            $id,
            $name,
            $level,
            $inventory
        )->unwrap();

        $this->assertInstanceOf(Player::class, $player);
        $this->assertSame($player->getId(), $id->getValue());
        $this->assertSame($player->getInventory(), $inventory);
        $this->assertSame($player->getLevel(), $level);
    }

    #[Test]
    public function should_fail_because_player_level_is_negative()
    {
        $id        = Identifier::create(1)->unwrap();
        $level     = -10;
        $inventory = $this->createMock(PlayerInventory::class);
        $name      = Name::create('Hilise')->unwrap();

        $result = Player::create(
            $id,
            $name,
            $level,
            $inventory
        );

        $this->assertNotInstanceOf(Player::class, $result->getData());
        $this->assertNull($result->getData());
        $this->assertTrue($result->isError());
        $this->assertSame($result->getMessage(), 'The level of a player cant be negative.');
    }
}
