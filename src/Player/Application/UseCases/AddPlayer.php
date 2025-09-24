<?php

declare(strict_types=1);

namespace AqHub\Player\Application\UseCases;

use AqHub\Shared\Domain\ValueObjects\{Result, Identifier};
use AqHub\Player\Domain\Repositories\PlayerRepository;
use AqHub\Player\Domain\ValueObjects\Name;

class AddPlayer
{
    public function __construct(private readonly PlayerRepository $playerRepository)
    {
    }

    public function execute(Identifier $identifier, Name $name): Result
    {
        return $this->playerRepository->persist($identifier, $name);
    }
}
