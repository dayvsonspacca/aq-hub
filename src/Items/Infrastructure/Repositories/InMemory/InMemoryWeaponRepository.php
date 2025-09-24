<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\InMemory;

use AqHub\Shared\Domain\ValueObjects\{IntIdentifier, Result};
use AqHub\Items\Domain\Repositories\WeaponRepository;
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Items\Domain\Enums\WeaponType;
use AqHub\Items\Domain\Entities\Weapon;

class InMemoryWeaponRepository implements WeaponRepository
{
    /** @var array<Weapon> $memory description */
    private array $memory = [];

    /**
     * @return Result<IntIdentifier|null>
     */
    public function persist(ItemInfo $itemInfo, WeaponType $type): Result
    {
        if ($this->findByName($itemInfo->getName())->isSuccess()) {
            return Result::error('A Weapon with same name already exists: ' . $itemInfo->getName(), null);
        }

        $id = IntIdentifier::create(
            end($this->memory) ? end($this->memory)->getId() + 1 : 1
        );

        $id = $id->unwrap();

        $weapon = Weapon::create($id, $itemInfo, $type)->unwrap();

        $this->memory[$weapon->getId()] = $weapon;

        return Result::success(null, $id);
    }

    /**
     * @return Result<Weapon|null>
     */
    public function findByName(string $name): Result
    {
        $weapons = array_filter($this->memory, fn ($weapon) => $weapon->getName() === $name);

        if (empty($weapons)) {
            return Result::error(null, null);
        }

        return Result::success(null, end($weapons));
    }
}
