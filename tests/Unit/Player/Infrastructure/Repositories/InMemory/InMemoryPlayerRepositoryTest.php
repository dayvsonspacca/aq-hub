<?php

declare(strict_types=1);

namespace Tests\Unit\Items\Domain\ValueObjects;

use AqHub\Player\Infrastructure\Repositories\InMemory\InMemoryPlayerRepository;
use AqHub\Shared\Domain\ValueObjects\Identifier;
use AqHub\Player\Domain\Entities\Player;
use PHPUnit\Framework\Attributes\Test;
use AqHub\Tests\Unit\TestCase;

final class InMemoryPlayerRepositoryTest extends TestCase
{
    #[Test]
    public function should_persist_a_player()
    {
        $repository = new InMemoryPlayerRepository();

        $identifier = Identifier::create(72894515)->getData();

        $result = $repository->persist($identifier);

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(Identifier::class, $result->unwrap());
        $this->assertSame(72894515, $result->unwrap()->getValue());
    }

    #[Test]
    public function should_fail_when_persist_a_player_with_same_id()
    {
        $repository = new InMemoryPlayerRepository();

        $identifier = Identifier::create(72894515)->getData();

        $repository->persist($identifier);
        $result = $repository->persist($identifier);

        $this->assertTrue($result->isError());
        $this->assertSame(null, $result->getData());
        $this->assertSame('A player with same id already exists: 72894515', $result->getMessage());
    }

    #[Test]
    public function should_find_player_by_identifier()
    {
        $repository = new InMemoryPlayerRepository();

        $identifier = Identifier::create(72894515)->getData();
        $repository->persist($identifier);

        $result = $repository->findByIdentifier($identifier);

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(Player::class, $result->getData());
    }

        #[Test]
    public function should_return_null_when_player_not_found_by_id()
    {
        $repository = new InMemoryPlayerRepository();

        $identifier = Identifier::create(72894515)->getData();

        $result = $repository->findByIdentifier($identifier);

        $this->assertTrue($result->isError());
        $this->assertSame(null, $result->getData());
    }
}
