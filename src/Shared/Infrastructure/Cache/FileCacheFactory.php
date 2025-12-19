<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Cache;

use AqHub\Core\Infrastructure\Cache\FileCache;

class FileCacheFactory
{
    public static function armors(string $cachePath): FileCache
    {
        return new FileCache(
            namespace: 'armors',
            directory: $cachePath,
            ttl: 0
        );
    }

    public static function capes(string $cachePath): FileCache
    {
        return new FileCache(
            namespace: 'capes',
            directory: $cachePath,
            ttl: 0
        );
    }
}
