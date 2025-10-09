<?php

declare(strict_types=1);

namespace AqHub\Items\Application\UseCases\Armor;

use AqHub\Items\Application\UseCases\Armor\AddArmor;

class ArmorUseCases
{
    public function __construct(
        public readonly AddArmor $add,
        public readonly FindAllArmors $findAll
    ) {
    }
}
