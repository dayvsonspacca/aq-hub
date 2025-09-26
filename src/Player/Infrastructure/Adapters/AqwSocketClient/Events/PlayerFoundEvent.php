<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Adapters\AqwSocketClient\Events;

use AqHub\Player\Domain\ValueObjects\Name;
use AqwSocketClient\Events\EventInterface;

class PlayerFoundEvent implements EventInterface
{
    public function __construct(public readonly Name $name) {}
}
