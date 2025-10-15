<?php

declare(strict_types=1);

namespace AqHub\Core\Interfaces;

/**
 * Interface that defines the contract for classes responsible for
 * registering definitions (dependencies, parameters, aliases, etc.)
 * within a Dependency Injection (DI) Container.
 *
 * Its primary purpose is to centralize and organize DI configuration,
 * separating it from the main application bootstrapping logic.
 */
interface DefinitionsInterface
{
    /**
     * Must return an array of definitions that the DI Container
     * can consume.
     *
     * @return array The dependency definitions to be registered in the container.
     */
    public static function dependencies(): array;
}