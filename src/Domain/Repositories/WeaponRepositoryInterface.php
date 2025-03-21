<?php

declare(strict_types=1);

namespace Aqwiki\Domain\Repositories;

use AqWiki\Domain\Entities;

interface WeaponRepositoryInterface
{
    public function getById(string $guid): ?Entities\Weapon;
}
