<?php

declare(strict_types=1);

namespace AqHub\Core\Infrastructure\Cache;

use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class FileCache
{
    private FilesystemTagAwareAdapter $cache;

    /**
     * @param string $namespace Organize cache in namespaces
     * @param string $directory A valid path to store cache
     * @param int $ttl Time-to-live, if `0` the cache should be stored permanently
     */
    public function __construct(
        public readonly string $namespace,
        public readonly string $directory,
        public readonly int $ttl = 0
    ) {
        $this->cache = new FilesystemTagAwareAdapter(
            namespace: $namespace,
            directory: $directory,
            defaultLifetime: $ttl
        );
    }

    /**
     * @param string $key The cache key
     * @param callable $callback The function that returns the value to be cached
     * @param null|int $expiresAfter Determines when the new cache will expire/invalidate. If `null` or `zero`, the cache should be stored permanently
     * @param array $cacheTags Tags used to reference this cache for invalidation purposes
     */
    public function get(string $key, callable $callback, ?int $expiresAfter = null, array $cacheTags = []): mixed
    {
        return $this->cache->get($key, function (ItemInterface $item) use ($callback, $expiresAfter, $cacheTags) {
            $item->tag($cacheTags);
            
            if (is_int($expiresAfter) && $expiresAfter > 0) {
                $item->expiresAfter($expiresAfter);
            }

            $result = $callback();

            $item->set($result);

            return $result;
        });
    }

    public function has(string $key): bool
    {
        return $this->cache->hasItem($key);
    }
}
