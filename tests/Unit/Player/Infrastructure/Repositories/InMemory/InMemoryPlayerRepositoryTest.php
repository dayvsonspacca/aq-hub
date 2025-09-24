<?php

declare(strict_types=1);

namespace Tests\Unit\Items\Domain\ValueObjects;

use AqHub\Player\Infrastructure\Repositories\InMemory\InMemoryPlayerRepository;
use AqHub\Shared\Domain\ValueObjects\IntIdentifier;
use AqHub\Player\Domain\ValueObjects\{Name ,Level};
use AqHub\Player\Domain\Entities\Player;
use PHPUnit\Framework\Attributes\Test;
use AqHub\Tests\Unit\TestCase;

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
        $this->assertInstanceOf(Player::class, $result->getData());
        $this->assertSame(72894515, $result->unwrap()->getId());
        $this->assertSame('Hilise', $result->unwrap()->getName());
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
    public function should_find_player_by_identifier()
    {
        $repository = new InMemoryPlayerRepository();

        $identifier = IntIdentifier::create(72894515)->unwrap();
        $name       = Name::create('Hilise')->unwrap();
        $level      = Level::create(1)->unwrap();

        $repository->persist($identifier, $name, $level);

        $result = $repository->findByIdentifier($identifier);

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(Player::class, $result->getData());
    }

    #[Test]
    public function should_return_null_when_player_not_found_by_id()
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
        $result = $repository->findAll();

        $this->assertTrue($result->isSuccess());
        $this->assertSame([], $result->getData());
    }


    #[Test]
    public function should_return_all_players()
    {
        $repository = new InMemoryPlayerRepository();
        $result = $repository->findAll();
        
        $identifier = IntIdentifier::create(72894515)->unwrap();
        $name       = Name::create('Hilise')->unwrap();
        $level      = Level::create(1)->unwrap();

        $repository->persist($identifier, $name, $level);

        $identifier = IntIdentifier::create(72894516)->unwrap();
        $name       = Name::create('Hilise2')->unwrap();
        $level      = Level::create(1)->unwrap();

        $repository->persist($identifier, $name, $level);

        $result = $repository->findAll();

        $this->assertTrue($result->isSuccess());
        $this->assertSame(2, count($result->getData()));
    }
}
