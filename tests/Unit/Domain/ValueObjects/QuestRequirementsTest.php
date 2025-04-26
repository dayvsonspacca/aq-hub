<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObjects;

use AqWiki\Domain\{ValueObjects, Entities, Exceptions};
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

final class QuestRequirementsTest extends TestCase
{
    private ValueObjects\QuestRequirements $requirements;
    private Entities\Weapon $weapon;

    protected function setUp(): void
    {
        $this->requirements = new ValueObjects\QuestRequirements();
        $this->weapon = $this->createMock(Entities\Weapon::class);
    }

    #[Test]
    public function should_add_requirement()
    {
        $this->requirements->add(new ValueObjects\LevelRequirement(20));
        $this->assertSame(1, $this->requirements->count());
        $this->assertSame(true, $this->requirements->has(new ValueObjects\LevelRequirement(20)));
    }

    #[Test]
    public function should_remove_requirement()
    {
        $this->requirements->add(new ValueObjects\LevelRequirement(20));
        $this->requirements->remove(new ValueObjects\LevelRequirement(20));
        $this->assertSame(0, $this->requirements->count());
        $this->assertSame(true, !$this->requirements->has(new ValueObjects\LevelRequirement(20)));
    }

    #[Test]
    public function find_requirement_should_return_true()
    {
        $this->requirements->add(new ValueObjects\ItemRequirement($this->weapon, 1));
        $this->assertSame(true, $this->requirements->has(new ValueObjects\ItemRequirement($this->weapon, 1)));
    }

    #[Test]
    public function should_throw_error_when_add_two_level_requirement()
    {
        $this->expectException(Exceptions\QuestException::tooManyLevelRequirements()::class);
        $this->expectExceptionMessage('A quest can not have 2 Level Requirements');

        $this->requirements->add(new ValueObjects\LevelRequirement(20));
        $this->requirements->add(new ValueObjects\LevelRequirement(20));
    }
}
