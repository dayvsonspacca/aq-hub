<?php

declare(strict_types=1);

namespace AqHub\Core;

use DI\Container as DIContainer;
use DI\ContainerBuilder;

class ContainerFactory
{
    public static function make(array $definitions): DIContainer
    {
        $builder = new ContainerBuilder();
        $builder->useAutowiring(true);
        $builder->addDefinitions($definitions);

        return $builder->build();
    }
}
