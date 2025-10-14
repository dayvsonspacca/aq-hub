<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Env;

use RuntimeException;

class Env
{
    public readonly AppMode $appMode;
    public readonly DatabaseConfig $dbConfig;

    private static ?self $instance = null;

    private function __construct(array $env)
    {
        $this->loadDatabaseConfig($env);
        $this->loadAppMode($env);
    }

    private function loadDatabaseConfig(array $env): void
    {
        $result = DatabaseConfig::fromEnvironment($env);

        if ($result->isError()) {
            throw new RuntimeException($result->getMessage());
        }

        $this->dbConfig = $result->getData();
    }

    public static function instance(array $env): self
    {
        if (self::$instance === null) {
            self::$instance = new self($env);
        }
        return self::$instance;
    }

    private function loadAppMode($env): void
    {
        $mode = $env['APP_MODE'];

        if ($mode === false) {
            throw new RuntimeException("Environment variable 'APP_MODE' is not set.");
        }

        $appMode = AppMode::fromString($mode);
        if ($appMode->isError()) {
            throw new RuntimeException($appMode->getMessage());
        }

        $this->appMode = $appMode->getData();
    }
}
