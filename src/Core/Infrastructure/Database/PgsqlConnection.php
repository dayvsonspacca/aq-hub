<?php

declare(strict_types=1);

namespace AqHub\Core\Infrastructure\Database;

use AqHub\Core\Env;
use PDO;
use RuntimeException;
use Throwable;

class PgsqlConnection
{
    private static ?self $instance = null;

    public readonly PDO $connection;

    private function __construct(Env $env)
    {
        $this->establishConnection($env);
    }

    public static function instance(Env $env, bool $forceReload = false): self
    {
        if (self::$instance === null || $forceReload) {
            self::$instance = new self($env);
        }
        return self::$instance;
    }

    private function establishConnection(Env $env): void
    {
        try {
            $config = PgsqlDatabaseConfig::fromEnvironment($env->vars)->unwrap();

            $host     = $config->host;
            $port     = $config->port;
            $dbname   = $config->name;
            $username = $config->user;
            $password = $config->password;
            $dsn      = "pgsql:host=$host;port=$port;dbname=$dbname";

            $options = [
                PDO::ATTR_PERSISTENT => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];

            $this->connection = new PDO($dsn, $username, $password, $options);
        } catch (Throwable $e) {
            throw new RuntimeException('PostgreSQL connection failed: ' . $e->getMessage());
        }
    }
}
