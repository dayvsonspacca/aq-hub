<?php

declare(strict_types=1);

namespace Tests\Unit\Player\Domain\Entities;

use AqHub\Player\Domain\Entities\Player;
use AqHub\Player\Domain\ValueObjects\{Level, Name, PlayerInventory};
use AqHub\Shared\Domain\ValueObjects\IntIdentifier;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class PlayerTest extends TestCase
{
    #[Test]
    public function should_create_player_instance_and_stores_it_data()
    {
        $id        = IntIdentifier::create(1)->unwrap();
        $level     = Level::create(100)->unwrap();
        $inventory = $this->createMock(PlayerInventory::class);
        $name      = Name::create('Hilise')->unwrap();

        $player = Player::create(
            $id,
            $name,
            $level,
            $inventory
        )->getData();

        $this->assertInstanceOf(Player::class, $player);
        $this->assertSame($player->getId(), $id->getValue());
        $this->assertSame($player->getInventory(), $inventory);
        $this->assertSame($player->getLevel(), $level->value);
        $this->assertSame($player->getName(), $name->value);
        $this->assertSame($player->toArray(), [
            'id' => $id->getValue(),
            'name' => $name->value,
            'level' => $level->value
        ]);
    }
}
