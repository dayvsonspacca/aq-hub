<?php

declare(strict_types=1);

namespace AqWiki\Domain\ValueObjects;

use AqWiki\Domain\{Contracts, Entities};

final class ItemRequirement implements Contracts\QuestRequirementInterface
{
    public function __construct(private readonly Entities\AqwItem $item)
    {
    }

    public function pass(Entities\Player $player): bool
    {
        return in_array($this->item, $player->items, true);
    }
}
