<?php

declare(strict_types=1);

namespace AqWiki\Domain\Entities;

final class Player
{
    /** @var Entities\AqwItem[] $items */
    public array $items;

    public function __construct(
        public readonly int $level,
        array $items = []
    ) {
        $this->items = $items;
    }
}
