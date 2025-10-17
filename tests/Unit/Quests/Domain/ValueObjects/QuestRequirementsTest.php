<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Quests\Domain\ValueObjects;

use AqHub\Quests\Domain\ValueObjects\{ItemRequirement, LevelRequirement, QuestRequirements};
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class QuestRequirementsTest extends TestCase
{
    #[Test]
    public function should_create_quest_requirements_instance_and_stores_it_data()
    {
        $levelRequirement  = $this->createMock(LevelRequirement::class);
        $questRequirements = new QuestRequirements([$levelRequirement]);

        $this->assertInstanceOf(QuestRequirements::class, $questRequirements);
        $this->assertTrue($questRequirements->has($levelRequirement));
        $this->assertSame(1, $questRequirements->count());
    }

    #[Test]
    public function should_can_add_a_requirement()
    {
        $levelRequirement  = $this->createMock(LevelRequirement::class);
        $questRequirements = new QuestRequirements([$levelRequirement]);
        $itemRequirement   = $this->createMock(ItemRequirement::class);

        $result = $questRequirements->add($itemRequirement);

        $this->assertNotInstanceOf(QuestRequirements::class, $result->getData());
        $this->assertNull($result->getData());
        $this->assertTrue($result->isSuccess());
        $this->assertSame(2, $questRequirements->count());
        $this->assertTrue($questRequirements->has($itemRequirement));
    }

    #[Test]
    public function should_fail_when_add_two_level_requirement()
    {
        $levelRequirement    = $this->createMock(LevelRequirement::class);
        $questRequirements   = new QuestRequirements([$levelRequirement]);
        $newLevelRequirement = $this->createMock(LevelRequirement::class);

        $result = $questRequirements->add($newLevelRequirement);

        $this->assertNotInstanceOf(QuestRequirements::class, $result->getData());
        $this->assertNull($result->getData());
        $this->assertTrue($result->isError());
        $this->assertSame(1, $questRequirements->count());
        $this->assertSame($result->getMessage(), 'A quest cant have more than one level requirement.');
    }

    #[Test]
    public function should_remove_quest_requirement()
    {
        $levelRequirement  = $this->createMock(LevelRequirement::class);
        $questRequirements = new QuestRequirements([$levelRequirement]);

        $this->assertSame(1, $questRequirements->count());

        $questRequirements->remove($levelRequirement);

        $this->assertSame(0, $questRequirements->count());
    }
}
