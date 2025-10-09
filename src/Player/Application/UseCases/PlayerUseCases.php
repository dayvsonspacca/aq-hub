<?php

declare(strict_types=1);

namespace AqHub\Player\Application\UseCases;

class PlayerUseCases
{
    public function __construct(
        public readonly AddPlayer $add,
        public readonly FindAllPlayers $findAll,
        public readonly MarkAsMined $markAsMined
    ) {
    }
}
