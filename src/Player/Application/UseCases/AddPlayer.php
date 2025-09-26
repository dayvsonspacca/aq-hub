<?php

declare(strict_types=1);

namespace AqHub\Player\Application\UseCases;

use AqHub\Player\Domain\Entities\Player;
use AqHub\Player\Domain\Repositories\PlayerRepository;
use AqHub\Player\Domain\ValueObjects\Name;
use AqHub\Player\Infrastructure\Http\Scrappers\CharpageScrapper;
use AqHub\Shared\Domain\ValueObjects\Result;

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

        [$identifier, $name, $level] = $result->getData();
        
        if ($result->isError()) {
            return $result;
        }

        $result = $result->getData();

        return $this->playerRepository->persist($identifier, $name, $level);
    }
}
