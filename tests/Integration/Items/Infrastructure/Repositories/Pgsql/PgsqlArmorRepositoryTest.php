<?php

declare(strict_types=1);

namespace AqHub\Tests\Integration\Items\Infrastructure\Repositories\Pgsql;

use AqHub\Core\{ContainerFactory, CoreDefinitions};
use AqHub\Core\Infrastructure\Database\DatabaseDefinitions;
use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\Repositories\Data\ArmorData;
use AqHub\Items\Domain\Repositories\Filters\ArmorFilter;
use AqHub\Items\Domain\ValueObjects\Name;
use AqHub\Items\Infrastructure\Container\ItemsDefinitions;
use AqHub\Items\Infrastructure\Repositories\Pgsql\PgsqlArmorRepository;
use AqHub\Shared\Domain\Enums\ItemTag;
use AqHub\Shared\Domain\ValueObjects\StringIdentifier;
use AqHub\Tests\TestCase;
use DI\Container;
use PHPUnit\Framework\Attributes\Test;

final class PgsqlArmorRepositoryTest extends TestCase
{
    private Container $container;
    private PgsqlArmorRepository $repository;

    protected function setUp(): void
    {
        $this->container = ContainerFactory::make(array_merge(
            CoreDefinitions::dependencies(),
            DatabaseDefinitions::dependencies(),
            ItemsDefinitions::dependencies()
        ));

        $this->repository = $this->container->get(PgsqlArmorRepository::class);
    }

    #[Test]
    public function should_create_pgsql_armor_repository()
    {
        $this->assertInstanceOf(PgsqlArmorRepository::class, $this->repository);
    }

    #[Test]
    public function should_return_null_when_not_find_by_identifier()
    {
        $armor = $this->repository->findByIdentifier(StringIdentifier::create('this is not a identifier')->unwrap());

        $this->assertNull($armor);
    }

    #[Test]
    public function should_find_armor_data_by_identifier()
    {
        $armor = $this->repository->findByIdentifier(StringIdentifier::create('f8aef10218bdf49e1c7215a17f8c13da')->unwrap());

        $this->assertNotNull($armor);
        $this->assertInstanceOf(ArmorData::class, $armor);
    }

    #[Test]
    public function should_find_all_match_armors_data()
    {
        $filter = new ArmorFilter();    
        $armors = $this->repository->findAll($filter);

        $this->assertIsArray($armors);
        $this->assertGreaterThan(0, count($armors));
    }

    #[Test]
    public function should_return_empty_array_when_filter_dont_find_anything()
    {
        $filter = new ArmorFilter();
        $filter->setName(Name::create('There is no item where an ILIKE will find right?')->unwrap());
        $armors = $this->repository->findAll($filter);

        $this->assertIsArray($armors);
        $this->assertCount(0, $armors);
    }

    #[Test]
    public function should_filter_by_rarities()
    {
        $filter = new ArmorFilter();
        $filter->setRarities([ItemRarity::Legendary]);
        $armors = $this->repository->findAll($filter);

        $this->assertIsArray($armors);
        $this->assertGreaterThanOrEqual(1, $armors);
    }
    
    #[Test]
    public function should_filter_by_tags()
    {
        $filter = new ArmorFilter();
        $filter->setTags([ItemTag::AdventureCoins]);
        $armors = $this->repository->findAll($filter);

        $this->assertIsArray($armors);
        $this->assertGreaterThanOrEqual(1, $armors);
    }
}
