<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Data;

use AqHub\Player\Domain\ValueObjects\{Name, Level};
use AqHub\Shared\Domain\ValueObjects\IntIdentifier;

class PlayerData
{
    public function __construct(
        public readonly IntIdentifier $identifier,
        public readonly Name $name,
        public readonly Level $level
    ) {}
}
