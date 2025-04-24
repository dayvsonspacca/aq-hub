<?php

declare(strict_types=1);

namespace AqWiki\Domain\Entities;

use AqWiki\Domain\Abstractions;

class Player extends Abstractions\Entity
{
    /** @var Abstractions\AqwItem[] $items */
    public array $items;

    public function __construct(
        public readonly int $level,
        array $items = []
    ) {
        $this->items = $items;
    }
}
