<?php

declare(strict_types=1);

namespace AqWiki\Domain\Entities;

use AqWiki\Domain\ValueObjects\QuestRequirements;

final class Quest
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $location,
        public readonly QuestRequirements $requirements
    ) {
    }
}
