<?php

declare(strict_types=1);

namespace AqHub\Quests\Domain\Entities;

use AqHub\Shared\Domain\ValueObjects\{Result, IntIdentifier};
use AqHub\Quests\Domain\ValueObjects\QuestRequirements;
use AqHub\Shared\Domain\Abstractions\Entity;

class Quest extends Entity
{
    private function __construct(
        IntIdentifier $id,
        private readonly string $name,
        private QuestRequirements $requirements
    ) {
        $this->id = $id;
    }

    /** @return Result<Quest> **/
    public static function create(IntIdentifier $id, string $name, QuestRequirements $requirements)
    {
        $name = trim($name);

        if (empty($name)) {
            return Result::error('The name of a quest cant be empty.', null);
        }

        return Result::success(null, new self($id, $name, $requirements));
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
