<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Env;

use RuntimeException;

class Env
{
    public readonly AppMode $appMode;

    private static ?self $instance = null;

    private function __construct()
    {
        $this->loadAppMode();
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