<?php

declare(strict_types=1);

namespace AqWiki\Domain\ValueObjects;

use AqWiki\Domain\{Contracts};

final class QuestRequirements implements \Countable
{
    /** @var Contracts\QuestRequirementInterface[] $ */
    private readonly array $requirements;

    public function add(Contracts\QuestRequirementInterface $requirement)
    {
        $this->requirements[] = $requirement;
    }

    public function has(Contracts\QuestRequirementInterface  $requirement): bool
    {
        return in_array($requirement, $this->requirements, true);
    }

    public function count(): int
    {
        return count($this->requirements);
    }
}
