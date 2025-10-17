<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Container;

use AqHub\Core\Interfaces\DefinitionsInterface;

class PlayerDefinitions implements DefinitionsInterface
{
    public static function dependencies(): array
    {
        return [];
    }
}
