<?php

declare(strict_types=1);

namespace AqWiki\Domain\Abstractions;

abstract class Entity
{
    protected string $guid;

    public function __construct(string $guid)
    {
        $this->guid = $guid;
    }

    public function getGuid(): string
    {
        return $this->guid;
    }
}
