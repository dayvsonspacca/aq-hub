<?php

declare(strict_types=1);

namespace AqHub\Shared\Domain\Abstractions;

use AqHub\Shared\Domain\ValueObjects\Identifier;

abstract class Entity
{
    protected Identifier $id;

    public function getId(): int
    {
        return $this->id->getValue();
    }
}
