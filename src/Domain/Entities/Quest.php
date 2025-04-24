<?php

declare(strict_types=1);

namespace AqWiki\Domain\Entities;

use AqWiki\Domain\{ValueObjects, Abstractions};

class Quest extends Abstractions\Entity
{
    /**
     * @var ValueObjects\QuestReward[] $rewards
     **/

    public function __construct(
        public readonly string $name,
        public readonly ?string $location,
        public readonly ValueObjects\QuestRequirements $requirements,
        public readonly array $rewards = []
    ) {
    }
}
