<?php

declare(strict_types=1);

namespace AqHub\Player\Application\UseCases;

use AqHub\Player\Domain\Repositories\PlayerRepository;
use AqHub\Player\Domain\ValueObjects\Name;

class MarkAsMined
{
    public function __construct(private readonly PlayerRepository $playerRepository)
    {
    }

    public function execute(Name $name)
    {
        $this->playerRepository->markAsMined($name);
    }
}
