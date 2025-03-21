<?php

declare(strict_types=1);

namespace AqWiki\Domain\Entities;

use AqWiki\Domain\{Enums, Exceptions, Abstractions};

class Weapon extends Abstractions\AqwItem
{
    private string $baseDamage;
    private Enums\WeaponType $type;

    public function getBaseDamage(): string
    {
        return $this->baseDamage;
    }

    public function changeBaseDamage(string $newBaseDamage)
    {
        if (empty($newBaseDamage)) {
            throw new Exceptions\InvalidItemAttributeException('The weapon base damage can not be empty.');
        }
        $parts = explode('-', $newBaseDamage);
        if (!(count($parts) === 2)) {
            throw new Exceptions\InvalidItemAttributeException('The weapon base damage needs to be in pattern `min-max`.');
        }

        $this->baseDamage = $newBaseDamage;
        return $this;
    }
}
