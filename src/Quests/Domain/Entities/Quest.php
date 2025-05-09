<?php

declare(strict_types=1);

namespace AqWiki\Quests\Domain\Entities;

use AqWiki\Quests\Domain\ValueObjects\QuestRequirements;
use AqWiki\Shared\Domain\ValueObjects\Result;
use AqWiki\Shared\Domain\Abstractions\Entity;
use AqWiki\Shared\Domain\Enums\ResultStatus;

class Quest extends Entity
{
    private function __construct(string $guid, private readonly string $name, private QuestRequirements $requirements)
    {
        $this->guid = $guid;
    }

    /** @return Result<Quest> **/
    public static function create(string $guid, string $name, QuestRequirements $requirements)
    {
        $guid = trim($guid);
        $name = trim($name);

        if (empty($guid)) {
            return new Result(ResultStatus::Error, 'The quest GUID cant be empty.', null);
        }
        if (empty($name)) {
            return new Result(ResultStatus::Error, 'The name of a quest cant be empty.', null);
        }

        return new Result(ResultStatus::Success, null, new self($guid, $name, $requirements));
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRequirements(): QuestRequirements
    {
        return $this->requirements;
    }
}
