<?php

declare(strict_types=1);

namespace Tests\Unit\Quests\Domain\Entities;

use AqHub\Quests\Domain\ValueObjects\QuestRequirements;
use AqHub\Shared\Domain\ValueObjects\Identifier;
use AqHub\Quests\Domain\Entities\Quest;
use PHPUnit\Framework\Attributes\Test;
use AqHub\Tests\Unit\TestCase;

final class QuestTest extends TestCase
{
    #[Test]
    public function should_create_quest_instance_and_stores_it_data()
    {
        $id           = Identifier::create(1)->getData();
        $name         = 'Awesome Quest';
        $requirements = $this->createMock(QuestRequirements::class);

        $quest = Quest::create($id, $name, $requirements)->unwrap();

        $this->assertInstanceOf(Quest::class, $quest);
        $this->assertSame($id->getValue(), $quest->getId());
        $this->assertSame($name, $quest->getName());
        $this->assertSame($requirements, $quest->getRequirements());
    }

    #[Test]
    public function should_fail_because_quest_name_is_empty()
    {
        $id           = Identifier::create(1)->getData();
        $name         = '';
        $requirements = $this->createMock(QuestRequirements::class);

        $result = Quest::create($id, $name, $requirements);

        $this->assertNotInstanceOf(Quest::class, $result->getData());
        $this->assertNull($result->getData());
        $this->assertSame($result->getMessage(), 'The name of a quest cant be empty.');
    }
}
