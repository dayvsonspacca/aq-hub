<?php

declare(strict_types=1);

namespace AqWiki\Infrastructure\Repositories\Fakes;

use AqWiki\Domain\{Entities, Repositories, Enums, ValueObjects};

final class FakeWeaponRepository implements Repositories\WeaponRepositoryInterface
{
    private array $database;

    public function __construct()
    {
        $necrotic = new Entities\Weapon();
        $necrotic
            ->defineBaseDamage('30-37')
            ->defineName('Necrotic Sword of Doom')
            ->defineSellback(new ValueObjects\GameCurrency(0, Enums\CurrencyType::AdventureCoins))
            ->defineDescription('The darkness compels… DOOOOOOOOOOOM!!!');

        $this->database = [
            'necrotic-sword-of-doom' => $necrotic
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
