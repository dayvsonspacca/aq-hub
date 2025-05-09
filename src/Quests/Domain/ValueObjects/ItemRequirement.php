<?php

declare(strict_types=1);

namespace AqWiki\Quests\Domain\ValueObjects;

use AqWiki\Quests\Domain\Contracts\QuestRequirementInterface;
use AqWiki\Items\Domain\Abstractions\AqwItem;
use AqWiki\Player\Domain\Entities\Player;

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
