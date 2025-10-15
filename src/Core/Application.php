<?php

declare(strict_types=1);

namespace AqHub\Core;

use AqHub\Core\Interfaces\DefinitionsInterface;
use DI\Container;
use RuntimeException;
use Throwable;

/**
 * The central coordinator (or Kernel) of the application.
 *
 * This class is responsible for bootstrapping the Dependency Injection (DI)
 * Container and providing access to the application's core services.
 */
final class Application
{
    /**
     * @param Container $container The configured Dependency Injection container instance.
     */
    private function __construct(
        private readonly Container $container
    ) {}

    /**
     * Factory method responsible for creating and configuring the Application instance.
     *
     * It uses a list of Definition classes to load all dependencies and parameters
     * into the DI container before the Application is ready to be used.
     *
     * @param string[] $definitions An array of classes that implement DefinitionsInterface, anything that don't implement it is ignored.
     * @return self|null The fully configured Application instance, or null if bootstrapping fails.
     */
    public static function build(string $name, array $definitions): ?self
    {
        try {
            $definitions = array_filter($definitions, fn(string $definition) => (new $definition) instanceof DefinitionsInterface);
            $config = array_map(fn(string $definition) => $definition::dependencies(), $definitions);

            return new self(ContainerFactory::make(array_merge(...$config)));
        } catch (Throwable $e) {
            throw new RuntimeException('Fatal error bootstraping ' . $name . ' cause: ' . $e->getMessage(), previous: $e);
        }
    }

    /**
     * Retrieves a service or dependency from the application container.
     *
     * This is the primary way external interfaces (API, CLI) will access the
     *
     * @template T
     * @param class-string<T> $id The class name or identifier of the service to retrieve.
     * @return T The resolved service instance.
     */
    public function get(string $id): mixed
    {
        return $this->container->get($id);
    }
}
