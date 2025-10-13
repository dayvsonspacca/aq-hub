<?php

declare(strict_types=1);

namespace AqHub\Items\Application\UseCases\Cape;

use AqHub\Items\Domain\Repositories\CapeRepository;
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Shared\Domain\Contracts\Cache;
use AqHub\Shared\Domain\ValueObjects\Result;

class AddCape
{
    public function __construct(
        private readonly CapeRepository $capeRepository,
        private readonly Cache $cache
    ) {
    }

    public function execute(ItemInfo $itemInfo, bool $canAccessBank): Result
    {
        $this->cache->invalidateTags(['invalidate-on-new-cape']);

        return $this->capeRepository->persist($itemInfo, $canAccessBank);
    }
}
