<?php

declare(strict_types=1);

namespace AqWiki\Player\Domain\Entities;

use AqWiki\Shared\Domain\Abstractions\Entity;

class Player extends Entity
{
    /** @var AqwItem[] $items */
    public array $items;

    public function __construct(
        public readonly int $level,
        array $items = []
    ) {
        $this->items = $items;
    }
}
