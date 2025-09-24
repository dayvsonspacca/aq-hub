<?php

declare(strict_types=1);

namespace AqHub\Quests\Domain\ValueObjects;

use AqHub\Player\Domain\Entities\Player;
use AqHub\Quests\Domain\Contracts\QuestRequirementInterface;
use AqHub\Quests\Domain\Entities\Quest;

class QuestRequirement implements QuestRequirementInterface
{
    public function __construct(private readonly Quest $quest)
    {
    }

    public function pass(Player $player): bool
    {
        foreach ($this->quest->getRequirements() as $requirement) {
            if (!$requirement->pass($player)) {
                return false;
            }
        }

        return true;
    }

    public function getQuest(): Quest
    {
        return $this->quest;
    }
}
