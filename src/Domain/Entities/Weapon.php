<?php

declare(strict_types=1);

namespace AqWiki\Domain\Entities;

use AqWiki\Domain\{Enums, ValueObjects, Abstractions, Traits};

class Weapon extends Abstractions\AqwItem
{
    private readonly string $baseDamage;
    private readonly Enums\WeaponType $type;

    public function __construct(
        string $name,
        string $description,
        ValueObjects\GameCurrency $price,
        ValueObjects\GameCurrency $sellback,
        ValueObjects\ItemTags $tags,
        string $baseDamage,
        Enums\WeaponType $type
    ) {
        parent::__construct($name, $description, $price, $sellback, $tags);

        $this->baseDamage = $baseDamage;
        $this->type = $type;
    }

    public function getBaseDamage(): string
    {
        return $this->baseDamage;
    }

    public function getType(): Enums\WeaponType
    {
        return $this->type;
    }
}
