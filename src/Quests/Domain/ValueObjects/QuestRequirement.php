<?php

declare(strict_types=1);

namespace AqWiki\Quests\Domain\ValueObjects;

use AqWiki\Quests\Domain\Contracts\QuestRequirementInterface;
use AqWiki\Player\Domain\Entities\Player;
use AqWiki\Quests\Domain\Entities\Quest;

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
}
