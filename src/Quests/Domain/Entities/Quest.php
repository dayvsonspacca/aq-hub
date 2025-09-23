<?php

declare(strict_types=1);

namespace AqWiki\Quests\Domain\Entities;

use AqWiki\Shared\Domain\ValueObjects\{Result, Identifier};
use AqWiki\Quests\Domain\ValueObjects\QuestRequirements;
use AqWiki\Shared\Domain\Abstractions\Entity;

class Quest extends Entity
{
    private function __construct(
        Identifier $id,
        private readonly string $name,
        private QuestRequirements $requirements
    ) {
        $this->id = $id;
    }

    /** @return Result<Quest> **/
    public static function create(Identifier $id, string $name, QuestRequirements $requirements)
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
