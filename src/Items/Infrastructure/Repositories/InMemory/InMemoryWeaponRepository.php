<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\InMemory;

use AqHub\Items\Domain\Entities\Weapon;
use AqHub\Items\Domain\Enums\WeaponType;
use AqHub\Items\Domain\Repositories\WeaponRepository;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Shared\Domain\ValueObjects\{Result, StringIdentifier};

class InMemoryWeaponRepository implements WeaponRepository
{
    /** @var array<Weapon> $memory description */
    private array $memory = [];

    /**
     * @return Result<Weapon|null>
     */
    public function persist(ItemInfo $itemInfo, WeaponType $type): Result
    {
        $id = ItemIdentifierGenerator::generate($itemInfo, Weapon::class)->unwrap();

        if ($this->findByIdentifier($id)->isSuccess()) {
            return Result::error('A Weapon with same identifier already exists: ' . $id->getValue(), null);
        }

        $weapon = Weapon::create($id, $itemInfo, $type)->unwrap();

        $this->memory[$weapon->getId()] = $weapon;

        return Result::success(null, $weapon);
    }

    /**
     * @return Result<Weapon|null>
     */
    public function findByIdentifier(StringIdentifier $identifier): Result
    {
        $weapons = array_filter($this->memory, fn ($weapon) => $weapon->getId() === $identifier->getValue());

        if (empty($weapons)) {
            return Result::error(null, null);
        }

        return Result::success(null, end($weapons));
    }
}
