<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Core;

use AqHub\Core\Application;
use AqHub\Core\CoreDefinations;
use AqHub\Core\Env;
use AqHub\Core\Interfaces\DefinitionsInterface;
use AqHub\Tests\TestCase;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;

final class ApplicationTest extends TestCase
{
    #[Test]
    public function should_bootstrap_application()
    {
        $app = Application::build('api', [CoreDefinations::class]);

        $this->assertInstanceOf(Application::class, $app);
    }

    #[Test]
    public function should_fail_bootstraping_application()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Fatal error bootstraping api cause: Invalid definition');
        $this->expectExceptionCode(0);

        Application::build('api', [
            CoreDefinations::class,
            (new class implements DefinitionsInterface {
                public static function dependencies(): array
                {
                    throw new InvalidArgumentException('Invalid definition');
                }
            })::class
        ]);
    }

    #[Test]
    public function should_can_access_container_definitions()
    {
        $app = Application::build('api', [CoreDefinations::class]);

        $this->assertInstanceOf(Env::class, $app->get(Env::class));
    }
}
