<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Env;

use RuntimeException;

class Env
{
    public readonly AppMode $appMode;
    public readonly DatabaseConfig $dbConfig;

    private static ?self $instance = null;

    private function __construct(private readonly array $envRawData)
    {
        $this->loadDatabaseConfig($envRawData);
        $this->loadAppMode($envRawData);
    }

    private function loadDatabaseConfig(array $envRawData): void
    {
        $result = DatabaseConfig::fromEnvironment($envRawData);

        if ($result->isError()) {
            throw new RuntimeException($result->getMessage());
        }

        $this->dbConfig = $result->getData();
    }

    public static function instance(array $envRawData, bool $forceRecreation = false): self
    {
        if (self::$instance === null || $forceRecreation) {
            self::$instance = new self($envRawData);
        }
        return self::$instance;
    }

    private function loadAppMode($envRawData): void
    {
        $mode = $envRawData['APP_MODE'] ?? false;

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
