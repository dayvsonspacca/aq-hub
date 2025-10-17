<?php

declare(strict_types=1);

namespace AqHub\Tests\Integration\Core\Infrastructure\Database;

use AqHub\Core\Env;
use AqHub\Core\Infrastructure\Database\PgsqlConnection;
use AqHub\Tests\TestCase;
use AqHub\Tests\Traits\HasContainer;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;

final class PgsqlConnectionTest extends TestCase
{
    use HasContainer;

    #[Test]
    public function should_return_same_instance()
    {
        $container = $this->container();
        $env       = $container->get(Env::class);

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
