<?php

declare(strict_types=1);

namespace AqHub\Items\Application\Armors\Commands;

use AqHub\Core\Infrastructure\Cache\FileCache;
use AqHub\Core\Result;
use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Domain\ValueObjects\ItemInfo;

class Add
{
    public function __construct(
        private readonly ArmorRepository $repository,
        private FileCache $cache
    ) {
    }

    public function execute(ItemInfo $info): Result
    {
        $result = $this->repository->save($info);
        if ($result->isSuccess()) {
            $this->cache->invalidateTags(['new-armor']);
        }

        return $result;
    }
}