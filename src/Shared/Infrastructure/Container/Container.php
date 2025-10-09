<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Container;

use AqHub\Items\Infrastructure\Container\ItemsDefinations;
use AqHub\Player\Infrastructure\Container\PlayerDefinations;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

class Container
{
    public static function build(): ContainerInterface
    {
        $builder = new ContainerBuilder();
        $builder->useAutowiring(true);
        $builder->addDefinitions(self::getDefinitions());
        
        return $builder->build();
    }

    private static function getDefinitions(): array
    {
        return array_merge(
            SharedDefinations::getDefinitions(),
            ItemsDefinations::getDefinitions(),
            PlayerDefinations::getDefinitions()
        );
    }
}