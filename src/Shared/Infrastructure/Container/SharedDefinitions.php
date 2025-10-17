<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Container;

use AqHub\Core\Interfaces\DefinitionsInterface;

class SharedDefinitions implements DefinitionsInterface
{
    public static function dependencies(): array
    {
        return [];
    }
}
