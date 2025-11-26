<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Core\Infrastructure\Cache;

use AqHub\Core\Infrastructure\Cache\FileCache;
use AqHub\Tests\TestCase;
use AqHub\Tests\Traits\HasContainer;
use PHPUnit\Framework\Attributes\Test;

final class FileCacheTest extends TestCase
{
    use HasContainer;

    private FileCache $cache;

    protected function setUp(): void
    {
        $this->cache = new FileCache('test', $this->container()->get('Path.Cache'), 5);
    }

    #[Test]
    public function should_create_file_cache()
    {
        $this->assertInstanceOf(FileCache::class, $this->cache);
    }

    #[Test]
    public function should_return_false_when_has_not_cache()
    {
        $this->assertFalse($this->cache->has('test-cache'));

        $callback = fn () => 'cached';

        $this->cache->get('test-cache', $callback, expiresAfter: 1);
    }

    #[Test]
    public function should_cache()
    {
        $callback = fn () => 'cached';

        $this->assertTrue($this->cache->has('test-cache'));
        $this->assertSame($this->cache->get('test-cache', $callback, expiresAfter: 1), 'cached');
    }

    // #[Test]
    // public function should_invalidate_tags()
    // {
    //     $callback = fn () => 'cached';
    //     $this->cache->get('test-cache2', $callback, expiresAfter: 3600, cacheTags: ['test-tag']);
    //     $this->assertTrue($this->cache->has('test-cache2'));

    //     $this->cache->invalidateTags(['test-tag']);

    //     $this->assertFalse($this->cache->has('test-cache2'));
    // }
}
