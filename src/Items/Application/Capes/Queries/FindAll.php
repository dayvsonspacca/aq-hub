<?php

declare(strict_types=1);

namespace AqHub\Items\Application\Capes\Queries;

use AqHub\Core\Infrastructure\Cache\FileCache;
use AqHub\Items\Application\Capes\Queries\Outputs\FindAllOutput;
use AqHub\Items\Domain\Repositories\CapeRepository;
use AqHub\Items\Domain\Repositories\Filters\CapeFilter;

class FindAll
{
    public function __construct(
        private readonly CapeRepository $repository,
        private FileCache $cache
    ) {
    }

    public function execute(CapeFilter $filter): FindAllOutput
    {
        $callback = function () use ($filter): FindAllOutput {
            $capes  = $this->repository->findAll($filter);
            $total  = $this->repository->countAll($filter);

            return new FindAllOutput(
                capes: $capes,
                total: $total
            );
        };

        return $this->cache->get(
            key: $filter->generateUniqueKey(),
            callback: $callback,
            expiresAfter: null,
            cacheTags: ['new-cape']
        );
    }
}
