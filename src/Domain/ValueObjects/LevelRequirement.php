<?php

declare(strict_types=1);

namespace AqWiki\Domain\ValueObjects;

use AqWiki\Domain\{Contracts, Entities};

final class LevelRequirement implements Contracts\QuestRequirementInterface
{
    public function __construct(private readonly int $level)
    {
    }

    public function pass(Entities\Player $player): bool
    {
        return $player->level >= $this->level;
    }
}
