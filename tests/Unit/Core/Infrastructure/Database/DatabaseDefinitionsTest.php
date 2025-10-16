<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Core\Infrastructure\Database;

use AqHub\Core\{ContainerFactory, CoreDefinitions, Env};
use AqHub\Core\Infrastructure\Database\DatabaseDefinitions;
use AqHub\Core\Infrastructure\Database\PgsqlConnection;
use AqHub\Tests\TestCase;
use DI\Container;
use PHPUnit\Framework\Attributes\Test;

final class DatabaseDefinitionsTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        $this->container = ContainerFactory::make(DatabaseDefinitions::dependencies());
    }

    #[Test]
    public function should_return_dependencies()
    {
        $dependencies = DatabaseDefinitions::dependencies();

        $this->assertCount(2, $dependencies);
    }

    #[Test]
    public function should_have_database_definitions()
    {
        $this->assertTrue($this->container->has(PgsqlConnection::class));
        $this->assertTrue($this->container->has('QueryBuilder.Pgsql'));
    }
}
