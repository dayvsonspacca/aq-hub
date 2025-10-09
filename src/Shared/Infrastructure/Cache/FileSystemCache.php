<?php

namespace AqHub\Shared\Infrastructure\Cache;

use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;

class FileSystemCache extends FilesystemTagAwareAdapter
{
    public function __construct(string $namespace, int $ttl)
    {
        parent::__construct($namespace, $ttl, CACHE_PATH);
    }
}