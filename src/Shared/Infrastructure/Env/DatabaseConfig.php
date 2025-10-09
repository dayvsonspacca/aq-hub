<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Env;

use AqHub\Shared\Domain\ValueObjects\Result;

class DatabaseConfig
{
    private function __construct(
        public readonly string $host,
        public readonly int $port,
        public readonly string $name,
        public readonly string $user,
        public readonly string $password
    ) {
    }

    /**
     * @return Result<DatabaseConfig|null>
     */
    public static function fromEnvironment(array $env): Result
    {
        $requiredKeys = [
            'DB_HOST' => 'string',
            'DB_PORT' => 'int',
            'DB_NAME' => 'string',
            'DB_USER' => 'string',
            'DB_PASSWORD' => 'string',
        ];

        $errors = [];
        $values = [];

        foreach ($requiredKeys as $key => $type) {
            $value = $env[$key] ?? null;

            if ($value === null || $value === '') {
                $errors[] = "Required environment variable '{$key}' is missing.";
                continue;
            }

            if ($type === 'int') {
                if (!is_numeric($value)) {
                    $errors[] = "Environment variable '{$key}' must be an integer, but got '{$value}'.";
                    continue;
                }
                $values[$key] = (int)$value;
            } else {
                $values[$key] = (string)$value;
            }
        }

        if (count($errors) > 0) {
            return Result::error('Database configuration validation failed: ' . implode(', ', $errors), null);
        }

        $config = new self(
            host: $values['DB_HOST'],
            port: $values['DB_PORT'],
            name: $values['DB_NAME'],
            user: $values['DB_USER'],
            password: $values['DB_PASSWORD']
        );

        return Result::success(null, $config);
    }
}
