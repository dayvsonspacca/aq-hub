<?php

declare(strict_types=1);

namespace AqWiki\Infrastructure\Repositories;

use AqWiki\Domain\{Entities, Repositories, Enums, ValueObjects};

final class FakeWeaponRepository implements Repositories\WeaponRepositoryInterface
{
    private array $database;

    public function __construct()
    {
        $this->database = [
            'necrotic-sword-of-doom' => (new Entities\Weapon(
                name: 'Necrotic Sword of Doom',
                price: null,
                sellback: new ValueObjects\GameCurrency(0, Enums\CurrencyType::AdventureCoins),
                description: 'The darkness compels… DOOOOOOOOOOOM!!!'
            ))->changeBaseDamage('30-37')
        ];
    }

    public function getById(string $guid): ?Entities\Weapon
    {
        return $this->database[$guid] ?? null;
    }

    public function persist(Entities\Weapon $weapon): void
    {
        $this->database[$weapon->name] = $weapon;
    }
}
