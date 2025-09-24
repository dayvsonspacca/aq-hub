<?php

declare(strict_types=1);

namespace AqHub\Shared\Domain\Abstractions;

use AqHub\Shared\Domain\ValueObjects\IntIdentifier;

abstract class Entity
{
    protected IntIdentifier $id;

    public function getId(): int
    {
        return $this->id->getValue();
    }
}
