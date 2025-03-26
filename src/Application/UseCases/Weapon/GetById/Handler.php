<?php

declare(strict_types=1);

namespace AqWiki\Application\UseCases\Weapon\GetById;

use AqWiki\Domain\Repositories\WeaponRepositoryInterface;

final class Handler
{
    public function __construct(WeaponRepositoryInterface $weaponRepository)
    {
        $weapon = $weaponRepository->getById('necrot-sword-of-doom');

        return $weapon;
    }
}
