<?php

declare(strict_types=1);

namespace AqWiki\Quests\Domain\Contracts;

use AqWiki\Player\Domain\Entities\Player;

interface QuestRequirementInterface
{
    public function pass(Player $player): bool;
}
