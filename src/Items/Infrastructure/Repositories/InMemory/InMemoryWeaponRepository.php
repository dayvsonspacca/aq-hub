<?php

declare(strict_types=1);

namespace AqWiki\Items\Infrastructure\Repositories\InMemory;

use AqWiki\Shared\Domain\ValueObjects\{Identifier, Result};
use AqWiki\Items\Domain\Repositories\WeaponRepository;
use AqWiki\Items\Domain\ValueObjects\ItemInfo;
use AqWiki\Items\Domain\Enums\WeaponType;
use AqWiki\Items\Domain\Entities\Weapon;

class InMemoryWeaponRepository implements WeaponRepository
{
    /** @var array<Weapon> $memory description */
    private array $memory = [];

    /**
     * @return Result<Identifier|null>
     */
    public function persist(ItemInfo $itemInfo, WeaponType $type): Result
    {
        if ($this->findByName($itemInfo->getName())->isSuccess()) {
            return Result::error('A Weapon with same name already exists: ' . $itemInfo->getName(), null);
        }

        $id = Identifier::create(
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
