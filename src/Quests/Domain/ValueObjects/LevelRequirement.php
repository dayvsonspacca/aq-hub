<?php

declare(strict_types=1);

namespace AqHub\Quests\Domain\ValueObjects;

use AqHub\Player\Domain\Entities\Player;
use AqHub\Quests\Domain\Contracts\QuestRequirementInterface;

class LevelRequirement implements QuestRequirementInterface
{
    public function __construct(private readonly int $level)
    {
    }

    public function pass(Player $player): bool
    {
        return $player->getLevel() >= $this->level;
    }

    public function getLevel(): int
    {
        return $this->level;
    }
}
