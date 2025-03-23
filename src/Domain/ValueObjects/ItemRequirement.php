<?php

declare(strict_types=1);

namespace AqWiki\Domain\ValueObjects;

use AqWiki\Domain\{Contracts, Entities, Abstractions};

final class ItemRequirement implements Contracts\QuestRequirementInterface
{
    public function __construct(private readonly Abstractions\AqwItem $item, private readonly int $amount)
    {
    }

    public function pass(Entities\Player $player): bool
    {
        return in_array($this->item, $player->items, true);
    }
}
