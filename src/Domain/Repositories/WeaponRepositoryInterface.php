<?php

declare(strict_types=1);

namespace AqWiki\Domain\Repositories;

use AqWiki\Domain\Entities;

interface WeaponRepositoryInterface
{
    public function findById(string $guid): ?Entities\Weapon;
    public function persist(Entities\Weapon $weapon);

}
