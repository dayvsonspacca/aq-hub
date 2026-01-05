<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Shared\Infrastructure\Cache;

use AqHub\Core\Infrastructure\Cache\FileCache;
use AqHub\Shared\Infrastructure\Cache\FileCacheFactory;
use AqHub\Tests\TestCase;
use AqHub\Tests\Traits\HasContainer;
use PHPUnit\Framework\Attributes\Test;

final class FileCacheFactoryTest extends TestCase
{
    use HasContainer;

    #[Test]
    public function should_return_default_armors_cache()
    {
        $armorsCache = FileCacheFactory::armors($this->container()->get('Path.Cache'));

        $this->assertInstanceOf(FileCache::class, $armorsCache);

        $this->assertSame($armorsCache->directory, $this->container()->get('Path.Cache'));
        $this->assertSame($armorsCache->namespace, 'armors');
        $this->assertSame($armorsCache->ttl, 0);
    }

    #[Test]
    public function should_return_default_capes_cache()
    {
        $capesCache = FileCacheFactory::capes($this->container()->get('Path.Cache'));

        $this->assertInstanceOf(FileCache::class, $capesCache);

        $this->assertSame($capesCache->directory, $this->container()->get('Path.Cache'));
        $this->assertSame($capesCache->namespace, 'capes');
        $this->assertSame($capesCache->ttl, 0);
    }
}
