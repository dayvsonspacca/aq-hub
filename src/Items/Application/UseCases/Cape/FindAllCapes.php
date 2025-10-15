<?php

declare(strict_types=1);

namespace AqHub\Items\Application\UseCases\Cape;

use AqHub\Items\Domain\Repositories\CapeRepository;
use AqHub\Items\Domain\Repositories\Data\CapeData;
use AqHub\Items\Domain\Repositories\Filters\CapeFilter;
use AqHub\Shared\Domain\Contracts\Cache;
use AqHub\Core\Result;
use Symfony\Contracts\Cache\ItemInterface;

class FindAllCapes
{
    public function __construct(
        private readonly CapeRepository $capeRepository,
        private readonly Cache $capesCache
    ) {
    }

    /**
     * @return Result<array<CapeData>>
     */
    public function execute(CapeFilter $filter): Result
    {
        $cacheKey = $filter->generateUniqueKey();

        $cachedResult = $this->capesCache->get($cacheKey, function (ItemInterface $item) use ($filter) {
            $item->expiresAfter(60);
            $item->tag('invalidate-on-new-cape');

            $result = $this->capeRepository->findAll($filter);

            if ($result->isError()) {
                return $result;
            }

            $item->set($result);

            return $result;
        });

        return $cachedResult;
    }
}
