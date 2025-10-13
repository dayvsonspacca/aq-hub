<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Cache;

use AqHub\Shared\Domain\Contracts\Cache;
use Closure;
use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;

class SymfonyCacheAdapter implements Cache
{
    public function __construct(private readonly FilesystemTagAwareAdapter $cache)
    {
    }

    public function get(string $key, Closure $callback): mixed
    {
        return $this->cache->get($key, $callback);
    }

    public function invalidateTags(array $tags)
    {
        $this->cache->invalidateTags($tags);
    }
}
