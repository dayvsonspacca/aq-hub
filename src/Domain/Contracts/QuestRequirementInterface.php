<?php

declare(strict_types=1);

namespace AqWiki\Domain\Contracts;

use AqWiki\Domain\Entities\Player;

interface QuestRequirementInterface
{
    public function pass(Player $player): bool;
}
