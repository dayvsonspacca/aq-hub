<?php

declare(strict_types=1);

namespace Tests\Domain\ValueObjects;

use AqWiki\Infrastructure\Repositories\FakeWeaponRepository;
use AqWiki\Domain\{ValueObjects, Repositories, Exceptions};
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\TestCase;

final class QuestRequirementsTest extends TestCase
{
    private ValueObjects\QuestRequirements $requirements;
    private Repositories\WeaponRepositoryInterface $weaponRepository;

    protected function setUp(): void
    {
        $this->requirements = new ValueObjects\QuestRequirements();
        $this->weaponRepository = new FakeWeaponRepository();
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
        $this->requirements->add(new ValueObjects\ItemRequirement($this->weaponRepository->getById('necrotic-sword-of-doom'), 1));
        $this->assertSame(true, $this->requirements->has(new ValueObjects\ItemRequirement($this->weaponRepository->getById('necrotic-sword-of-doom'), 1)));
    }

    #[Test]
    public function should_throw_error_when_add_two_level_requirement()
    {
        $this->expectException(Exceptions\DuplicateQuestRequirement::level()::class);
        $this->expectExceptionMessage('A quest can not have 2 Level Requirements');

        $this->requirements->add(new ValueObjects\LevelRequirement(20));
        $this->requirements->add(new ValueObjects\LevelRequirement(20));
    }
}
