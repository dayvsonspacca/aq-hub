<?php

declare(strict_types=1);

namespace AqHub\Quests\Domain\ValueObjects;

use AqHub\Quests\Domain\Contracts\QuestRequirementInterface;
use AqHub\Items\Domain\Abstractions\AqwItem;
use AqHub\Player\Domain\Entities\Player;

class ItemRequirement implements QuestRequirementInterface
{
    public function __construct(
        private readonly AqwItem $item
    ) {
    }

    public function pass(Player $player): bool
    {
        return $player->getInventory()->has($this->item);
    }

    public function getItem(): AqwItem
    {
        return $this->item;
    }
}
