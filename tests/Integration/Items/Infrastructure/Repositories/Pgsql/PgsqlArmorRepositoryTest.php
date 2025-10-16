<?php

declare(strict_types=1);

namespace AqHub\Tests\Integration\Items\Infrastructure\Repositories\Pgsql;

use AqHub\Core\ContainerFactory;
use AqHub\Core\CoreDefinitions;
use AqHub\Core\Infrastructure\Database\DatabaseDefinitions;
use AqHub\Items\Domain\Entities\Armor;
use AqHub\Items\Domain\Repositories\Data\ArmorData;
use AqHub\Items\Infrastructure\Container\ItemsDefinitions;
use AqHub\Items\Infrastructure\Repositories\Pgsql\PgsqlArmorRepository;
use AqHub\Shared\Domain\ValueObjects\StringIdentifier;
use AqHub\Tests\TestCase;
use DI\Container;
use PHPUnit\Framework\Attributes\Test;

final class PgsqlArmorRepositoryTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        $this->container = ContainerFactory::make(array_merge(
            CoreDefinitions::dependencies(),
            DatabaseDefinitions::dependencies(),
            ItemsDefinitions::dependencies()
        ));
    }

    #[Test]
    public function should_create_pgsql_armor_repository()
    {
        $repository = $this->container->get(PgsqlArmorRepository::class);

        $this->assertInstanceOf(PgsqlArmorRepository::class, $repository);
    }

    #[Test]
    public function should_return_null_when_not_find_by_identifier()
    {
        $repository = $this->container->get(PgsqlArmorRepository::class);

        $armor = $repository->findByIdentifier(StringIdentifier::create('this is not a identifier')->unwrap());

        $this->assertNull($armor);
    }

    #[Test]
    public function should_find_armor_data_by_identifier()
    {
        $repository = $this->container->get(PgsqlArmorRepository::class);

        $armor = $repository->findByIdentifier(StringIdentifier::create('f8aef10218bdf49e1c7215a17f8c13da')->unwrap());

        $this->assertNotNull($armor);
        $this->assertInstanceOf(ArmorData::class, $armor);
    }
}