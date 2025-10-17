<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Core;

use AqHub\Core\Env;
use AqHub\Tests\TestCase;
use AqHub\Tests\Traits\HasContainer;
use PHPUnit\Framework\Attributes\Test;

final class EnvTest extends TestCase
{
    use HasContainer;

    #[Test]
    public function should_load_via_container()
    {
        $container = $this->container();

        $env = $container->get(Env::class);

        $this->assertInstanceOf(Env::class, $env);
        $this->assertNotEmpty($env->vars);
    }
}
