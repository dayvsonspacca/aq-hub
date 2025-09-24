<?php

declare(strict_types=1);

namespace AqHub\Quests\Domain\ValueObjects;

use AqHub\Quests\Domain\Contracts\QuestRequirementInterface;
use AqHub\Shared\Domain\ValueObjects\Result;
use ArrayIterator;
use Countable;
use IteratorAggregate;

class QuestRequirements implements Countable, IteratorAggregate
{
    /** @var array<string, QuestRequirementInterface> $requirements */
    private array $requirements = [];

    /** @var array<string, QuestRequirementInterface> $requirements */
    public function __construct(array $requirements = [])
    {
        foreach ($requirements as $requirement) {
            $this->requirements[md5(serialize($requirement))] = $requirement;
        }
    }

    /** @return Result<null> */
    public function add(QuestRequirementInterface $requirement)
    {
        if ($requirement instanceof LevelRequirement && $this->has($requirement)) {
            return Result::error('A quest cant have more than one level requirement.', null);
        }

        $this->requirements[md5(serialize($requirement))] = $requirement;
        return Result::success(null, null);
    }

    public function remove(QuestRequirementInterface $requirement)
    {
        unset($this->requirements[md5(serialize($requirement))]);
    }

    public function has(QuestRequirementInterface $requirement): bool
    {
        return in_array(md5(serialize($requirement)), array_keys($this->requirements), true);
    }

    public function count(): int
    {
        return count($this->requirements);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->requirements);
    }
}
