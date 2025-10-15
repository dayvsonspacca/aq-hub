<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Shared\Infrastructure\Env;

use AqHub\Shared\Infrastructure\Env\AppMode;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class AppModeTest extends TestCase
{
    #[Test]
    public function should_create_app_mode_when_dev_or_prod()
    {
        $mode = AppMode::fromString('dev');

        $this->assertTrue($mode->isSuccess());
        $this->assertEquals(AppMode::Development, $mode->unwrap());

        $mode = AppMode::fromString('prod');

        $this->assertTrue($mode->isSuccess());
        $this->assertEquals(AppMode::Production, $mode->unwrap());
    }

    #[Test]
    public function should_fail_when_app_mode_not_dev_or_prod()
    {
        $mode = AppMode::fromString('test');

        $this->assertTrue($mode->isError());
        $this->assertEquals(null, $mode->getData());
    }
}
