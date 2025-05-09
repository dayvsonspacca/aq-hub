<?php

declare(strict_types=1);

namespace AqWiki\Shared\Domain\Abstractions;

abstract class Entity
{
    protected string $guid;

    public function getGuid(): string
    {
        return $this->guid;
    }
}
