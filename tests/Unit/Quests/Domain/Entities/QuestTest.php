<?php

declare(strict_types=1);

namespace Tests\Unit\Quests\Domain\Entities;

use AqWiki\Quests\Domain\ValueObjects\QuestRequirements;
use AqWiki\Quests\Domain\Entities\Quest;
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

final class QuestTest extends TestCase
{
    #[Test]
    public function should_create_quest_instance_and_stores_it_data()
    {
        $guid = '1';
        $name = 'Awesome Quest';
        $requirements = $this->createMock(QuestRequirements::class);

        $quest = Quest::create($guid, $name, $requirements)->unwrap();

        $this->assertInstanceOf(Quest::class, $quest);
        $this->assertSame($guid, $quest->getGuid());
        $this->assertSame($name, $quest->getName());
        $this->assertSame($requirements, $quest->getRequirements());
    }

    #[Test]
    public function should_fail_because_quest_guid_is_empty()
    {
        $guid = '';
        $name = 'Awesome Quest';
        $requirements = $this->createMock(QuestRequirements::class);

        $result = Quest::create($guid, $name, $requirements);

        $this->assertNotInstanceOf(Quest::class, $result->getData());
        $this->assertNull($result->getData());
        $this->assertSame($result->getMessage(), 'The quest GUID cant be empty.');
    }

    #[Test]
    public function should_fail_because_quest_name_is_empty()
    {
        $guid = '1';
        $name = '';
        $requirements = $this->createMock(QuestRequirements::class);

        $result = Quest::create($guid, $name, $requirements);

        $this->assertNotInstanceOf(Quest::class, $result->getData());
        $this->assertNull($result->getData());
        $this->assertSame($result->getMessage(), 'The name of a quest cant be empty.');
    }
}
