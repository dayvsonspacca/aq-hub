<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Cache;

use Symfony\Contracts\Cache\CacheInterface;
use AqHub\Shared\Domain\Contracts\Cache;
use Closure;

class SymfonyCacheAdapter implements Cache
{
    public function __construct(private readonly CacheInterface $cache)
    {
    }

    public function get(string $key, Closure $callback): mixed
    {
        return $this->cache->get($key, $callback);
    }
}