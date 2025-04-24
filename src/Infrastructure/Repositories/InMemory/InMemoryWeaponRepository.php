<?php

declare(strict_types=1);

namespace AqWiki\Infrastructure\Repositories\InMemory;

use AqWiki\Domain\{Entities, Repositories, Exceptions};

final class InMemoryWeaponRepository implements Repositories\WeaponRepositoryInterface
{
    private array $database = [];

    public function findById(string $guid): ?Entities\Weapon
    {
        return $this->database[$guid] ?? null;
    }

    public function persist(Entities\Weapon $weapon): void
    {
        if (isset($this->database[$weapon->guid])) {
            throw Exceptions\RepositoryException::alreadyExists('InMemoryWeaponRepository');
        }

        $this->database[$weapon->guid] = $weapon;
    }
}
