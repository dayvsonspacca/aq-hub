<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entities;

use AqWiki\Domain\{ValueObjects, Entities};
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

final class QuestTest extends TestCase
{
    private Entities\Quest $quest;
    private string $name = 'Awesome Quest';
    private ValueObjects\QuestRequirements $requirements;

    protected function setUp(): void
    {
        parent::setUp();

        $this->requirements = $this->createMock(ValueObjects\QuestRequirements::class);

        $this->quest = new Entities\Quest(
            $this->name,
            $this->requirements
        );
    }

    #[Test]
    public function it_stores_and_returns_the_name(): void
    {
        $this->assertSame($this->name, $this->quest->getName());
    }

    #[Test]
    public function it_stores_and_returns_the_quest_requirements(): void
    {
        $this->assertSame($this->requirements, $this->quest->getRequirements());
    }
}
