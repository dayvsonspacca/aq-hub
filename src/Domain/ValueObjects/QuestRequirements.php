<?php

declare(strict_types=1);

namespace AqWiki\Domain\ValueObjects;

use AqWiki\Domain\{Contracts, ValueObjects, Exceptions};

class QuestRequirements implements \Countable, \IteratorAggregate
{
    /** @var array<string, Contracts\QuestRequirementInterface> $requirements */
    private array $requirements = [];

    /** @var array<string, Contracts\QuestRequirementInterface> $requirements */
    public function __construct(array $requirements = [])
    {
        $this->requirements = $requirements;
    }

    public function add(Contracts\QuestRequirementInterface $requirement)
    {
        if ($requirement instanceof ValueObjects\LevelRequirement && $this->has($requirement)) {
            throw Exceptions\QuestException::tooManyLevelRequirements();
        }

        $this->requirements[md5(serialize($requirement))] = $requirement;
    }

    public function remove(Contracts\QuestRequirementInterface $requirement)
    {
        unset($this->requirements[md5(serialize($requirement))]);
    }

    public function has(Contracts\QuestRequirementInterface  $requirement): bool
    {
        return in_array(md5(serialize($requirement)), array_keys($this->requirements), true);
    }

    public function count(): int
    {
        return count($this->requirements);
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->requirements);
    }
}
