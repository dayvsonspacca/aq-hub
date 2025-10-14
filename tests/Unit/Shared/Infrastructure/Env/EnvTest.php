<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\ValueObjects;

use AqHub\Shared\Infrastructure\Env\{AppMode, DatabaseConfig, Env};
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;

final class EnvTest extends TestCase
{
    #[Test]
    public function should_create_env_and_stores_it_data()
    {
        $env = Env::instance([
            'APP_MODE' => 'dev',
            'DB_HOST' => 'db',
            'DB_PORT' => 5432,
            'DB_NAME' => 'postgres',
            'DB_USER' => 'aqhub',
            'DB_PASSWORD' => 'aqhub'
        ]);

        $this->assertInstanceOf(Env::class, $env);
        $this->assertInstanceOf(AppMode::class, $env->appMode);
        $this->assertInstanceOf(DatabaseConfig::class, $env->dbConfig);

        $this->assertSame(AppMode::Development, $env->appMode);
        $this->assertSame('db', $env->dbConfig->host);
        $this->assertSame(5432, $env->dbConfig->port);
        $this->assertSame('postgres', $env->dbConfig->name);
        $this->assertSame('aqhub', $env->dbConfig->user);
        $this->assertSame('aqhub', $env->dbConfig->password);
    }

    #[Test]
    public function should_throw_exception_when_app_mode_not_set()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Environment variable 'APP_MODE' is not set.");

        $env = Env::instance([
            'DB_HOST' => 'db',
            'DB_PORT' => 5432,
            'DB_NAME' => 'postgres',
            'DB_USER' => 'aqhub',
            'DB_PASSWORD' => 'aqhub'
        ], true);
    }

    #[Test]
    public function should_throw_exception_when_database_config_results_in_error()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Database configuration validation failed: Required environment variable 'DB_HOST' is missing.");

        $env = Env::instance([
            'APP_MODE' => 'dev',
            'DB_PORT' => 5432,
            'DB_NAME' => 'postgres',
            'DB_USER' => 'aqhub',
            'DB_PASSWORD' => 'aqhub'
        ], true);
    }

    #[Test]
    public function should_throw_exception_when_app_mode_results_in_error()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Mode not defined: ultra-production');

        $env = Env::instance([
            'APP_MODE' => 'ultra-production',
            'DB_HOST' => 'db',
            'DB_PORT' => 5432,
            'DB_NAME' => 'postgres',
            'DB_USER' => 'aqhub',
            'DB_PASSWORD' => 'aqhub'
        ], true);
    }
}
