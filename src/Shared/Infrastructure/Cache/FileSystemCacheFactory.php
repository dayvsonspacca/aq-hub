<?php

namespace AqHub\Shared\Infrastructure\Cache;

use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;

class FileSystemCacheFactory 
{
    public static function create(string $namespace, int $ttl)
    {
        return new FilesystemTagAwareAdapter($namespace, $ttl, CACHE_PATH);
    }
}