<?php

declare(strict_types=1);

namespace AqWiki\Infrastructure\Repositories;

use AqWiki\Domain\{Entities, Repositories, Enums, ValueObjects};

final class FakeWeaponRepository implements Repositories\WeaponRepositoryInterface
{
    public function getById(string $guid): ?Entities\Weapon
    {
        return $this->fakeDatabase($guid);
    }

    private function fakeDatabase(string $guid): ?Entities\Weapon
    {
        $necrotic = (new Entities\Weapon(
            name: 'Necrotic Sword of Doom',
            rarity: Enums\ItemRarity::LegendaryItemRarity,
            price: null,
            sellback: new ValueObjects\GameCurrency(0, Enums\CurrencyType::AdventureCoins),
            description: 'The darkness compels… DOOOOOOOOOOOM!!!'
        ))->changeBaseDamage('30-37');

        $database = [
            'necrotic-sword-of-doom' => $necrotic,
        ];

        return $database[$guid] ?? null;
    }
}
