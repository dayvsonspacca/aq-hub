<?php

declare(strict_types=1);

namespace AqWiki\Domain\Entities;

use AqWiki\Domain\{Enums, Exceptions, Abstractions, Traits};

class Weapon extends Abstractions\AqwItem
{
    use Traits\HasRarity;

    private string $baseDamage;
    private Enums\WeaponType $type;

    public function getBaseDamage(): string
    {
        return $this->baseDamage;
    }

    public function defineBaseDamage(string $newBaseDamage)
    {
        if (empty($newBaseDamage)) {
            throw Exceptions\AqwItemException::invalidAttribute('The weapon base damage can not be empty.');
        }
        $parts = explode('-', $newBaseDamage);
        if (!(count($parts) === 2)) {
            throw Exceptions\AqwItemException::invalidAttribute('The weapon base damage needs to be in pattern `min-max`.');
        }

        $this->baseDamage = $newBaseDamage;
        return $this;
    }
}
