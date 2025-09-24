<?php

declare(strict_types=1);

namespace AqHub\Quests\Domain\Contracts;

use AqHub\Player\Domain\Entities\Player;

interface QuestRequirementInterface
{
    public function pass(Player $player): bool;
}
