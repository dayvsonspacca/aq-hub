<?php

declare(strict_types=1);

namespace AqHub\Player\Application\UseCases;

use AqHub\Player\Domain\Repositories\Data\PlayerData;
use AqHub\Player\Domain\Repositories\Filters\PlayerFilter;
use AqHub\Player\Domain\Repositories\PlayerRepository;
use AqHub\Shared\Domain\Contracts\Cache;
use AqHub\Shared\Domain\ValueObjects\Result;
use Symfony\Contracts\Cache\ItemInterface;

class FindAllPlayers
{
    public function __construct(
        private readonly PlayerRepository $playerRepository,
        private readonly Cache $playerCache
    ) {
    }

    /**
     * @return Result<array<PlayerData>>
     */
    public function execute(PlayerFilter $filter): Result
    {
        $cacheKey = $filter->generateUniqueKey();

        $cachedResult = $this->playerCache->get($cacheKey, function (ItemInterface $item) use ($filter): Result {

            $item->expiresAfter(null);
            $item->tag('invalidate-on-new-player');

            $result = $this->playerRepository->findAll($filter);

            if ($result->isError()) {
                return $result;
            }

            $item->set($result);

            return $result;
        });

        return $cachedResult;
    }
}
