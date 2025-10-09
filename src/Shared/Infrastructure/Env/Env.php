<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Env;

use RuntimeException;

class Env
{
    public readonly AppMode $appMode;
    public readonly DatabaseConfig $dbConfig;

    private static ?self $instance = null;

    private function __construct()
    {
        $this->loadDatabaseConfig();
        $this->loadAppMode();
    }

    private function loadDatabaseConfig(): void
    {
        $result = DatabaseConfig::fromEnvironment($_ENV);

        if ($result->isError()) {
            throw new RuntimeException($result->getMessage());
        }

        $this->dbConfig = $result->getData();
    }

    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function loadAppMode(): void
    {
        $mode = $_ENV['APP_MODE'];

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
