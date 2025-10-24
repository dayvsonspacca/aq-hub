<?php

declare(strict_types=1);

namespace AqHub\Core;

use AqHub\Core\Interfaces\DefinitionsInterface;

use function DI\factory;

use Dotenv\Dotenv;

class CoreDefinitions implements DefinitionsInterface
{
    public static function dependencies(): array
    {
        $rootDir = __DIR__ . '/../../';

        $dotenv = Dotenv::createImmutable($rootDir);
        $dotenv->load();

        return [
            'Path.Root' => $rootDir,
            'Path.Cache' => $rootDir . 'cache',
            'Path.Logs' => $rootDir . 'logs',
            Env::class => factory([Env::class, 'load'])->parameter('vars', $_ENV)->parameter('forceReload', false)
        ];
    }
}
