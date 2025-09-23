<?php

declare(strict_types=1);

namespace Tests\Unit\Items\Domain\ValueObjects;

use AqWiki\Items\Domain\ValueObjects\{Description, ItemTags, ItemInfo, Name};
use AqWiki\Items\Infrastructure\Repositories\InMemory\InMemoryWeaponRepository;
use AqWiki\Shared\Domain\ValueObjects\Identifier;
use AqWiki\Items\Domain\Enums\WeaponType;
use AqWiki\Items\Domain\Entities\Weapon;
use AqWiki\Shared\Domain\Enums\TagType;
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

final class InMemoryWeaponRepositoryTest extends TestCase
{
    #[Test]
    public function should_persist_an_weapon()
    {
        $repository = new InMemoryWeaponRepository();

        $name        = 'Necrotic Sword of Doom';
        $description = 'The darkness compels… DOOOOOOOOOOOM!!!';
        $tags        = new ItemTags([TagType::AdventureCoins]);

        $itemInfo = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags)->unwrap();

        $weaponType = WeaponType::Sword;

        $result = $repository->persist($itemInfo, $weaponType);

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(Identifier::class, $result->unwrap());
        $this->assertSame(1, $result->unwrap()->getValue());
    }

    #[Test]
    public function should_fail_when_persist_an_weapon_with_same_name()
    {
        $repository = new InMemoryWeaponRepository();

        $name        = 'Necrotic Sword of Doom';
        $description = 'The darkness compels… DOOOOOOOOOOOM!!!';
        $tags        = new ItemTags([TagType::AdventureCoins]);

        $itemInfo = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags)->unwrap();

        $weaponType = WeaponType::Sword;

        $repository->persist($itemInfo, $weaponType);
        $result = $repository->persist($itemInfo, $weaponType);

        $this->assertTrue($result->isError());
        $this->assertSame(null, $result->getData());
        $this->assertSame('A Weapon with same name already exists: Necrotic Sword of Doom', $result->getMessage());
    }

    #[Test]
    public function should_find_weapon_by_name()
    {
        $repository = new InMemoryWeaponRepository();

        $name        = 'Necrotic Sword of Doom';
        $description = 'The darkness compels… DOOOOOOOOOOOM!!!';
        $tags        = new ItemTags([TagType::AdventureCoins]);
        $itemInfo    = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags)->unwrap();
        $weaponType  = WeaponType::Sword;
        $repository->persist($itemInfo, $weaponType);

        $result = $repository->findByName($name);

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(Weapon::class, $result->unwrap());
        $this->assertSame($name, $result->unwrap()->getName());
    }

    #[Test]
    public function should_return_null_when_weapon_not_found_by_name()
    {
        $repository = new InMemoryWeaponRepository();

        $name = 'Necrotic Sword of Doom';

        $result = $repository->findByName($name);

        $this->assertTrue($result->isError());
        $this->assertSame(null, $result->getData());
    }
}
