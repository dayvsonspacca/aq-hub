<?php

declare(strict_types=1);

namespace AqHub\Player\Application\UseCases;

use AqHub\Items\Infrastructure\Services\CharpageScrapper;
use AqHub\Player\Domain\Repositories\PlayerRepository;
use AqHub\Shared\Domain\ValueObjects\Result;
use AqHub\Player\Domain\ValueObjects\Name;
use AqHub\Player\Domain\Entities\Player;

class AddPlayer
{
    public function __construct(private readonly PlayerRepository $playerRepository)
    {
    }

    /**
     * @return Result<Player|null>
     */
    public function execute(Name $name): Result
    {
        $result = CharpageScrapper::findPlayerData($name);

        if ($result->isError()) {
            return $result;
        }

        $result = $result->getData();

        return $this->playerRepository->persist($result->identifier, $result->name, $result->level);
    }
}
