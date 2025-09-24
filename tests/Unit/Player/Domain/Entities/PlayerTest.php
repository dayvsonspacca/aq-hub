<?php

declare(strict_types=1);

namespace Tests\Unit\Player\Domain\Entities;

use AqHub\Player\Domain\ValueObjects\{Level, PlayerInventory, Name};
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
        $level     = Level::create(100)->getData();
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
        $this->assertSame($player->getLevel(), $level->value);
    }
}
