<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Cache;

use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;
use Symfony\Contracts\Cache\CacheInterface;

class SymfonyFileSystemCacheFactory
{
    public static function create(string $namespace, int $ttl): CacheInterface
    {
        return new FilesystemTagAwareAdapter($namespace, $ttl, CACHE_PATH);
    }
}