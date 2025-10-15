<?php

declare(strict_types=1);

namespace AqHub\Core;

use AqHub\Core\Interfaces\DefinitionsInterface;

final class CoreDefinations implements DefinitionsInterface
{
    public static function dependencies(): array
    {
        $rootDir = __DIR__ . '/../../';

        return [
            'Path.Root' => $rootDir,
            'Path.Cache' => $rootDir . '/cache',
            'Path.Logs' => $rootDir . '/logs'
        ];
    }
}