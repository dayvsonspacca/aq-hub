<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Database;

use AqHub\Shared\Infrastructure\Env\Env;
use Aura\SqlQuery\QueryFactory;
use PDO;
use PDOException;
use RuntimeException;

class Connection
{
    private static ?Connection $instance = null;

    private function __construct(Env $env)
    {
        $this->builder = new QueryFactory('pgsql');
        $this->establishConnection($env);
    }

    public static function instance(Env $env): self
    {
        if (self::$instance === null) {
            self::$instance = new self($env);
        }
        return self::$instance;
    }

    private PDO $connection;
    public QueryFactory $builder;

    private function establishConnection(Env $env): void
    {
        $config = $env->dbConfig;

        $host     = $config->host;
        $port     = $config->port;
        $dbname   = $config->name;
        $username = $config->user;
        $password = $config->password;

        try {
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

            $options = [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];

            $pdo              = new PDO($dsn, $username, $password, $options);
            $this->connection = $pdo;
        } catch (PDOException $e) {
            throw new RuntimeException('Database connection failed: ' . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchOne(string $sql, array $params = []): ?array
    {
        $result = $this->query($sql, $params)->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function execute(string $sql, array $params = []): bool
    {
        return $this->query($sql, $params) !== false;
    }
}
