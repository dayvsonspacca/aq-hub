<?php

declare(strict_types=1);

namespace AqHub\Items\Application\Armors\Queries;

use AqHub\Core\Infrastructure\Cache\FileCache;
use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Domain\Repositories\Filters\ArmorFilter;

class FindAll
{
    public function __construct(
        private readonly ArmorRepository $repository,
        private FileCache $cache
    ) {
    }

    public function execute(ArmorFilter $filter)
    {
        $callback = fn () => $this->repository->findAll($filter);

        return $this->cache->get(
            key: $filter->generateUniqueKey(),
            callback: $callback,
            expiresAfter: null,
            cacheTags: ['new-armor']
        );
    }
}
