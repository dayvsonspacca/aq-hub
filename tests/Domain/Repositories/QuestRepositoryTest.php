<?php

declare(strict_types=1);

namespace Tests\Domain\ValueObjects;

use AqWiki\Infrastructure\Repositories\FakeQuestRepository;
use AqWiki\Domain\{Repositories, Entities};
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\TestCase;

final class QuestRepositoryTest extends TestCase
{
    private Repositories\QuestRepositoryInterface $questRepository;

    protected function setUp(): void
    {
        $this->questRepository = new FakeQuestRepository();
    }

    #[Test]
    public function should_returns_a_quest()
    {
        $weapon = $this->questRepository->getById('a-dark-knight');

        $this->assertInstanceOf(Entities\Quest::class, $weapon);
    }

    #[Test]
    public function should_returns_null_when_not_found()
    {
        $weapon = $this->questRepository->getById('Juggernaut Items of Nulgath');

        $this->assertNull($weapon);
    }
}
