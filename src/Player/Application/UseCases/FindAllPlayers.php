<?php

declare(strict_types=1);

namespace AqHub\Player\Application\UseCases;

use AqHub\Player\Domain\Repositories\Data\PlayerData;
use AqHub\Player\Domain\Repositories\Filters\PlayerFilter;
use AqHub\Player\Domain\Repositories\PlayerRepository;
use AqHub\Shared\Domain\ValueObjects\Result;

class FindAllPlayers
{
    public function __construct(private readonly PlayerRepository $playerRepository)
    {
    }

    /**
     * @return Result<array<PlayerData>>
     */
    public function execute(PlayerFilter $filter): Result
    {
        return $this->playerRepository->findAll($filter);
    }
}
