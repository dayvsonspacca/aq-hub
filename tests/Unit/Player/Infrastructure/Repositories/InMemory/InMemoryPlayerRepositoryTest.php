<?php

declare(strict_types=1);

namespace Tests\Unit\Items\Domain\ValueObjects;

use AqHub\Player\Domain\ValueObjects\{Level, Name};
use AqHub\Player\Infrastructure\Data\PlayerData;
use AqHub\Player\Infrastructure\Repositories\Filters\PlayerFilter;
use AqHub\Player\Infrastructure\Repositories\InMemory\InMemoryPlayerRepository;
use AqHub\Shared\Domain\ValueObjects\IntIdentifier;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class InMemoryPlayerRepositoryTest extends TestCase
{
    #[Test]
    public function should_persist_a_player()
    {
        $repository = new InMemoryPlayerRepository();

        $identifier = IntIdentifier::create(72894515)->unwrap();
        $name       = Name::create('Hilise')->unwrap();
        $level      = Level::create(1)->unwrap();

        $result = $repository->persist($identifier, $name, $level);

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(PlayerData::class, $result->getData());
        $this->assertSame(72894515, $result->unwrap()->identifier->getValue());
        $this->assertSame('HILISE', $result->unwrap()->name->value);
    }

    #[Test]
    public function should_fail_when_persist_a_player_with_same_id()
    {
        $repository = new InMemoryPlayerRepository();

        $identifier = IntIdentifier::create(72894515)->unwrap();
        $name       = Name::create('Hilise')->unwrap();
        $level      = Level::create(1)->unwrap();

        $repository->persist($identifier, $name, $level);
        $result = $repository->persist($identifier, $name, $level);

        $this->assertTrue($result->isError());
        $this->assertSame(null, $result->getData());
        $this->assertSame('A player with same id already exists: 72894515', $result->getMessage());
    }

    #[Test]
    public function should_find_player_data_by_identifier()
    {
        $repository = new InMemoryPlayerRepository();

        $identifier = IntIdentifier::create(72894515)->unwrap();
        $name       = Name::create('Hilise')->unwrap();
        $level      = Level::create(1)->unwrap();

        $repository->persist($identifier, $name, $level);

        $result = $repository->findByIdentifier($identifier);

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(PlayerData::class, $result->getData());
    }

    #[Test]
    public function should_return_null_when_player_data_not_found_by_id()
    {
        $repository = new InMemoryPlayerRepository();

        $identifier = IntIdentifier::create(72894515)->unwrap();

        $result = $repository->findByIdentifier($identifier);

        $this->assertTrue($result->isError());
        $this->assertSame(null, $result->getData());
    }

    #[Test]
    public function should_return_empty_array_when_find_all_without_persist()
    {
        $repository = new InMemoryPlayerRepository();
        $result     = $repository->findAll(new PlayerFilter());

        $this->assertTrue($result->isSuccess());
        $this->assertSame([], $result->getData());
    }

    #[Test]
    public function should_return_all_players()
    {
        $repository = new InMemoryPlayerRepository();
        $identifier = IntIdentifier::create(72894515)->unwrap();
        $name       = Name::create('Hilise')->unwrap();
        $level      = Level::create(1)->unwrap();

        $repository->persist($identifier, $name, $level);

        $identifier = IntIdentifier::create(72894516)->unwrap();
        $name       = Name::create('Hilise2')->unwrap();
        $level      = Level::create(1)->unwrap();

        $repository->persist($identifier, $name, $level);

        $result = $repository->findAll(new PlayerFilter());

        $this->assertTrue($result->isSuccess());
        $this->assertSame(2, count($result->getData()));
    }

    #[Test]
    public function should_return_only_mined_players()
    {
        $repository = new InMemoryPlayerRepository();
        $identifier = IntIdentifier::create(72894515)->unwrap();
        $name       = Name::create('Hilise')->unwrap();
        $level      = Level::create(1)->unwrap();

        $repository->persist($identifier, $name, $level);

        $identifier = IntIdentifier::create(72894516)->unwrap();
        $name       = Name::create('Hilise2')->unwrap();
        $level      = Level::create(1)->unwrap();

        $repository->persist($identifier, $name, $level);
        $repository->markAsMined($name);

        $result = $repository->findAll(new PlayerFilter(mined: true));

        $this->assertTrue($result->isSuccess());
        $this->assertSame(1, count($result->getData()));
        $this->assertSame('HILISE2', $result->getData()[0]->name->value);
    }

    #[Test]
    public function should_fail_when_try_mine_same_player()
    {
        $repository = new InMemoryPlayerRepository();
        $identifier = IntIdentifier::create(72894515)->unwrap();
        $name       = Name::create('Hilise')->unwrap();
        $level      = Level::create(1)->unwrap();

        $repository->persist($identifier, $name, $level);
        $repository->markAsMined($name);
        $result = $repository->markAsMined($name);

        $this->assertTrue($result->isError());
    }
}
