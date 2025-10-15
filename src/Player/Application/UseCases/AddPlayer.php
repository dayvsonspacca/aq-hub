<?php

declare(strict_types=1);

namespace AqHub\Player\Application\UseCases;

use AqHub\Player\Domain\Entities\Player;
use AqHub\Player\Domain\Repositories\PlayerRepository;
use AqHub\Player\Domain\ValueObjects\Name;
use AqHub\Player\Infrastructure\Http\Scrappers\CharpageScrapper;
use AqHub\Shared\Domain\Contracts\Cache;
use AqHub\Core\Result;

class AddPlayer
{
    public function __construct(
        private readonly PlayerRepository $playerRepository,
        private readonly Cache $cache
    ) {
    }

    /**
     * @return Result<Player|null>
     */
    public function execute(Name $name): Result
    {
        $result = CharpageScrapper::findPlayerData($name);

        [$identifier, $name, $level] = $result->getData();

        if ($result->isError()) {
            return $result;
        }

        $this->cache->invalidateTags(['invalidate-on-new-player']);

        $result = $result->getData();

        return $this->playerRepository->persist($identifier, $name, $level);
    }
}
