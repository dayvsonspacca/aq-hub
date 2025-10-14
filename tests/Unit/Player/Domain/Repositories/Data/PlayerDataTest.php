<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Player\Domain\Repositories\Data;;

use AqHub\Player\Domain\Repositories\Data\PlayerData;
use AqHub\Player\Domain\ValueObjects\Level;
use AqHub\Player\Domain\ValueObjects\Name;
use AqHub\Shared\Domain\ValueObjects\IntIdentifier;
use AqHub\Tests\Unit\TestCase;
use DateTime;
use PHPUnit\Framework\Attributes\Test;

final class PlayerDataTest extends TestCase
{
    #[Test]
    public function should_create_player_data_instance_and_stores_it_data()
    {
        $id = IntIdentifier::create(1)->unwrap();
        $name = Name::create('Hilise')->unwrap();
        $level = Level::create(100)->unwrap();
        $registeredAt = new DateTime('2025-10-14');
        $mined = true;

        $playerData = new PlayerData(
            $id,
            $name,
            $level,
            $registeredAt,
            $mined
        );

        $this->assertInstanceOf(PlayerData::class, $playerData);
        $this->assertSame($playerData->identifier, $id);
        $this->assertSame($playerData->name, $name);
        $this->assertSame($playerData->level, $level);
        $this->assertSame($playerData->registeredAt, $registeredAt);
        $this->assertSame($playerData->mined, $mined);
    }

    #[Test]
    public function should_return_correct_array_format_on_to_array()
    {
        $id = IntIdentifier::create(1)->unwrap();
        $name = Name::create('Hilise')->unwrap();
        $level = Level::create(100)->unwrap();
        $registeredAt = new DateTime('2025-10-14');
        $mined = true;

        $playerData = new PlayerData(
            $id,
            $name,
            $level,
            $registeredAt,
            $mined
        );

        $this->assertSame($playerData->toArray(), [
            'id' => $id->getValue(),
            'name' => $name->value,
            'level' => $level->value,
            'created_at' => $registeredAt->format('Y-m-d H:i:s'),
            'mined' => $mined
        ]);
    }
}
