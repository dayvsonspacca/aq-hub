<?php

declare(strict_types=1);

namespace AqHub\Player\Application\UseCases;

use AqHub\Items\Infrastructure\Services\CharpageScrapper;
use AqHub\Player\Domain\Repositories\PlayerRepository;
use AqHub\Player\Domain\ValueObjects\{Name, Level};
use AqHub\Shared\Domain\ValueObjects\Result;
use AqHub\Player\Domain\Entities\Player;

class AddPlayer
{
    public function __construct(private readonly PlayerRepository $playerRepository) {}

    /**
     * @return Result<Player|null>
     */
    public function execute(Name $name, Level $level): Result
    {
        $identifier = CharpageScrapper::findIdentifier($name);

        if ($identifier->isError()) {
            return $identifier;
        }

        return $this->playerRepository->persist($identifier->getData(), $name, $level);
    }
}
