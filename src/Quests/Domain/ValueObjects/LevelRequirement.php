<?php

declare(strict_types=1);

namespace AqWiki\Quests\Domain\ValueObjects;

use AqWiki\Quests\Domain\Contracts\QuestRequirementInterface;
use AqWiki\Player\Domain\Entities\Player;

class LevelRequirement implements QuestRequirementInterface
{
    public function __construct(private readonly int $level)
    {
    }

    public function pass(Player $player): bool
    {
        return $player->level >= $this->level;
    }
}
