<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Core;

use AqHub\Core\Env;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class EnvTest extends TestCase
{
    #[Test]
    public function should_load_with_vars()
    {
        $env = Env::load(
            [
                'APP_MODE'  => 'dev'
            ],
            forceReload: true
        );

        $this->assertInstanceOf(Env::class, $env);
        $this->assertNotEmpty($env->vars);
    }
}
