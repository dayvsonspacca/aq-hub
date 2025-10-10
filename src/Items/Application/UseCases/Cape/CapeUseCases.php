<?php

declare(strict_types=1);

namespace AqHub\Items\Application\UseCases\Cape;

class CapeUseCases
{
    public function __construct(
        public readonly AddCape $add,
        public readonly FindAllCapes $findAll
    ) {
    }
}
