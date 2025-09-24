<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Container;

interface ContainerRegistry
{
    public static function build(): array;

    public static function registerRepositories(): array;
    public static function registerCommands(): array;
    public static function registerUseCases(): array;
}