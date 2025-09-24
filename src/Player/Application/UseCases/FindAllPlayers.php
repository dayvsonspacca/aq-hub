<?php

declare(strict_types=1);

namespace AqHub\Player\Application\UseCases;

use AqHub\Player\Domain\Repositories\PlayerRepository;
use AqHub\Shared\Domain\ValueObjects\Result;
use AqHub\Player\Domain\Entities\Player;

class FindAllPlayers
{
    public function __construct(private readonly PlayerRepository $playerRepository)
    {
    }

    /**
     * @return Result<array<Player>>
     */
    public function execute(): Result
    {
        return $this->playerRepository->findAll();
    }
}
