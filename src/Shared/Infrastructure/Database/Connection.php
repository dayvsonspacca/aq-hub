<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Database;

use AqHub\Shared\Domain\ValueObjects\Result;
use PDOException;
use PDO;

class Connection
{
    private PDO $connection;

    private function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public static function connect(
        string $host,
        string $dbname,
        string $username,
        string $password,
        int $port = 5432
    ): Result {
        try {
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return Result::success('Connection established.', new self($pdo));
        } catch (PDOException $e) {
            return Result::error($e->getMessage(), null);
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
