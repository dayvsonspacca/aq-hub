<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Log;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

class FileLoggerFactory
{
    public static function create(string $channelName, string $fileName, Level $level): Logger
    {
        if (!defined('LOGS_PATH')) {
            throw new \RuntimeException("The constant LOGS_PATH is not defined. Define the base log path during bootstrap.");
        }
        
        $logger = new Logger($channelName);
        $logPath = LOGS_PATH . '/' . $fileName;

        $logger->pushHandler(new StreamHandler($logPath, $level));
        
        return $logger;
    }
}