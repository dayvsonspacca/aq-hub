<?php

declare(strict_types=1);

namespace AqHub\Tests\Integration\Core\Infrastructure\Database;

use AqHub\Core\Env;
use AqHub\Core\Infrastructure\Database\PgsqlConnection;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;

final class PgsqlConnectionTest extends TestCase
{
    #[Test]
    public function should_return_same_instance()
    {
        $env = Env::load(
            [
                'DB_HOST' => 'db',
                'DB_PORT' => 5432,
                'DB_NAME' => 'postgres',
                'DB_USER' => 'aqhub',
                'DB_PASSWORD' => 'aqhub'
            ],
            forceReload: true
        );

        $connection1 = PgsqlConnection::instance($env);
        $connection2 = PgsqlConnection::instance($env);

        $this->assertSame($connection1, $connection2);
    }

    #[Test]
    public function should_fail_when_connect_without_env()
    {
        $this->expectException(RuntimeException::class);

        $env = Env::load([], forceReload: true);
        PgsqlConnection::instance($env, forceReload: true);
    }
}
