<?php

declare(strict_types=1);

namespace Tests\Unit\Items\Domain\ValueObjects;

use AqHub\Items\Domain\Entities\Weapon;
use AqHub\Items\Domain\Enums\{ItemRarity, WeaponType};
use AqHub\Items\Domain\Repositories\Data\WeaponData;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Items\Infrastructure\Repositories\InMemory\InMemoryWeaponRepository;
use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Shared\Domain\ValueObjects\StringIdentifier;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class InMemoryWeaponRepositoryTest extends TestCase
{
    #[Test]
    public function should_persist_an_weapon()
    {
        $repository = new InMemoryWeaponRepository();

        $name        = 'Necrotic Sword of Doom';
        $description = 'The darkness compels… DOOOOOOOOOOOM!!!';
        $tags        = new ItemTags([TagType::AdventureCoins]);

        $itemInfo = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags, ItemRarity::Legendary)->unwrap();

        $weaponType = WeaponType::Sword;

        $result = $repository->persist($itemInfo, $weaponType);

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(WeaponData::class, $result->unwrap());
        $this->assertSame('87f3da3ead50247f5b890b3291b45c1a426537117e8a60703a70c5ae4f0481ad', $result->unwrap()->identifier->getValue());
    }

    #[Test]
    public function should_fail_when_persist_an_weapon_with_same_identifier()
    {
        $repository = new InMemoryWeaponRepository();

        $name        = 'Necrotic Sword of Doom';
        $description = 'The darkness compels… DOOOOOOOOOOOM!!!';
        $tags        = new ItemTags([TagType::AdventureCoins]);

        $itemInfo = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags, ItemRarity::Legendary)->unwrap();

        $weaponType = WeaponType::Sword;

        $repository->persist($itemInfo, $weaponType);
        $result = $repository->persist($itemInfo, $weaponType);

        $this->assertTrue($result->isError());
        $this->assertSame(null, $result->getData());
        $this->assertSame('A Weapon with same identifier already exists: 87f3da3ead50247f5b890b3291b45c1a426537117e8a60703a70c5ae4f0481ad', $result->getMessage());
    }

    #[Test]
    public function should_find_weapon_by_identifier()
    {
        $repository = new InMemoryWeaponRepository();

        $name        = 'Necrotic Sword of Doom';
        $description = 'The darkness compels… DOOOOOOOOOOOM!!!';
        $tags        = new ItemTags([TagType::AdventureCoins]);
        $itemInfo    = ItemInfo::create(Name::create($name)->unwrap(), Description::create($description)->unwrap(), $tags, ItemRarity::Legendary)->unwrap();
        $weaponType  = WeaponType::Sword;

        $weapon = $repository->persist($itemInfo, $weaponType)->unwrap();

        $result = $repository->findByIdentifier(ItemIdentifierGenerator::generate($itemInfo, Weapon::class)->unwrap());

        $this->assertTrue($result->isSuccess());
        $this->assertInstanceOf(WeaponData::class, $result->unwrap());
        $this->assertSame($name, $result->unwrap()->name->value);
    }

    #[Test]
    public function should_return_null_when_weapon_not_found_by_identifier()
    {
        $repository = new InMemoryWeaponRepository();

        $name = 'Necrotic Sword of Doom';
        $id   = StringIdentifier::create($name)->unwrap();

        $result = $repository->findByIdentifier($id);

        $this->assertTrue($result->isError());
        $this->assertSame(null, $result->getData());
    }
}
