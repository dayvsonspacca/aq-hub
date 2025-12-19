<?php

declare(strict_types=1);

namespace AqHub\Core;

use DI\{Container as DIContainer, ContainerBuilder};

class ContainerFactory
{
    /** @param array[] $definitions Expect to be a array of DefinitionsInterface::dependencies() */
    public static function make(array $definitions): DIContainer
    {
        $builder = new ContainerBuilder();
        $builder->useAutowiring(true);
        foreach($definitions as $definition) {
            $builder->addDefinitions($definition);
        }

        return $builder->build();
    }
}
