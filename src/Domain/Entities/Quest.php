<?php

declare(strict_types=1);

namespace AqWiki\Domain\Entities;

use AqWiki\Domain\{ValueObjects, Abstractions, Utils, Exceptions};

class Quest extends Abstractions\Entity
{
    private readonly string $name;
    private ValueObjects\QuestRequirements $requirements;

    public function __construct(
        string $guid,
        string $name,
        ValueObjects\QuestRequirements $requirements
    ) {
        $this->guid = Utils\Strings::ifEmptyThrow($guid, new Exceptions\QuestException('The quest id can not be empty.'));
        $this->name = Utils\Strings::ifEmptyThrow($name, new Exceptions\QuestException('The name of a quest can not be empty.'));
        $this->requirements = $requirements;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRequirements(): ValueObjects\QuestRequirements
    {
        return $this->requirements;
    }
}
