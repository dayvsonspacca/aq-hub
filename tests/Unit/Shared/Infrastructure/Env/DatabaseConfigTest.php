<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Shared\Infrastructure\Env;

use AqHub\Shared\Infrastructure\Env\DatabaseConfig;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class DatabaseConfigTest extends TestCase
{
    /**
     * @return array<string, array<string, string|int>>
     */
    private function getDefaultEnv(): array
    {
        return [
            'DB_HOST' => 'localhost',
            'DB_PORT' => 5432,
            'DB_NAME' => 'test_db',
            'DB_USER' => 'test_user',
            'DB_PASSWORD' => 'secret',
        ];
    }

    #[Test]
    public function should_create_config_successfully_when_all_variables_are_present_and_valid()
    {
        $env = $this->getDefaultEnv();

        $result = DatabaseConfig::fromEnvironment($env);

        $this->assertTrue($result->isSuccess());

        /** @var DatabaseConfig $config */
        $config = $result->unwrap();

        $this->assertInstanceOf(DatabaseConfig::class, $config);
        $this->assertEquals('localhost', $config->host);
        $this->assertEquals(5432, $config->port);
        $this->assertEquals('test_db', $config->name);
        $this->assertEquals('test_user', $config->user);
        $this->assertEquals('secret', $config->password);
    }

    #[Test]
    public function should_create_config_when_port_is_passed_as_string()
    {
        $env            = $this->getDefaultEnv();
        $env['DB_PORT'] = '3306';

        $result = DatabaseConfig::fromEnvironment($env);

        $this->assertTrue($result->isSuccess());

        $config = $result->unwrap();

        $this->assertIsInt($config->port);
        $this->assertEquals(3306, $config->port);
    }

    #[Test]
    public function should_fail_when_a_required_variable_is_missing()
    {
        $env = $this->getDefaultEnv();
        unset($env['DB_USER']);

        $result = DatabaseConfig::fromEnvironment($env);

        $this->assertTrue($result->isError());
        $this->assertStringContainsString("Required environment variable 'DB_USER' is missing.", $result->getMessage());
    }

    #[Test]
    public function should_fail_when_a_required_variable_is_empty()
    {
        $env            = $this->getDefaultEnv();
        $env['DB_HOST'] = '';

        $result = DatabaseConfig::fromEnvironment($env);

        $this->assertTrue($result->isError());
        $this->assertStringContainsString("Required environment variable 'DB_HOST' is missing.", $result->getMessage());
    }

    #[Test]
    public function should_fail_when_port_is_not_numeric()
    {
        $env            = $this->getDefaultEnv();
        $env['DB_PORT'] = 'not-a-number';

        $result = DatabaseConfig::fromEnvironment($env);

        $this->assertTrue($result->isError());
        $this->assertStringContainsString("Environment variable 'DB_PORT' must be an integer, but got 'not-a-number'.", $result->getMessage());
    }

    #[Test]
    public function should_list_all_errors_when_multiple_variables_are_invalid()
    {
        $env = [
            'DB_HOST' => '',
            'DB_PORT' => 'abc',
            'DB_NAME' => 'db',
            'DB_USER' => '',
            'DB_PASSWORD' => 'pwd',
        ];

        $result = DatabaseConfig::fromEnvironment($env);

        $this->assertTrue($result->isError());
        $message = $result->getMessage();

        $this->assertStringContainsString("Required environment variable 'DB_HOST' is missing", $message);
        $this->assertStringContainsString("Environment variable 'DB_PORT' must be an integer", $message);
        $this->assertStringContainsString("Required environment variable 'DB_USER' is missing", $message);
    }
}
