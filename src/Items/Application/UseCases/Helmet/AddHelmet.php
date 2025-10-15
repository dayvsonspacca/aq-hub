<?php

declare(strict_types=1);

namespace AqHub\Items\Application\UseCases\Helmet;

use AqHub\Items\Domain\Repositories\HelmetRepository;
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Shared\Domain\Contracts\Cache;
use AqHub\Core\Result;

class AddHelmet
{
    public function __construct(
        private readonly HelmetRepository $helmetRepository,
        private readonly Cache $cache
    ) {
    }

    public function execute(ItemInfo $itemInfo): Result
    {
        $this->cache->invalidateTags(['invalidate-on-new-cape']);

        return $this->helmetRepository->persist($itemInfo);
    }
}
