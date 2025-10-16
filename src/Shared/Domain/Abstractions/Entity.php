<?php

declare(strict_types=1);

namespace AqHub\Shared\Domain\Abstractions;

use AqHub\Shared\Domain\Contracts\Identifier;

abstract class Entity
{
    protected Identifier $id;

    public function getId()
    {
        return $this->id->getValue();
    }
}
