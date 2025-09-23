<?php

declare(strict_types=1);

namespace AqWiki\Shared\Domain\Abstractions;

use AqWiki\Shared\Domain\ValueObjects\Identifier;

abstract class Entity
{
    protected Identifier $id;

    public function getId(): int
    {
        return $this->id->getValue();
    }
}
