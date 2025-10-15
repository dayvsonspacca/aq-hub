<?php

declare(strict_types=1);

namespace AqHub\Tests\Traits;

use AqHub\Core\ContainerFactory;
use AqHub\Core\CoreDefinitions;

trait HasContainer
{
    public function container()
    {
        return ContainerFactory::make(array_merge(
            CoreDefinitions::dependencies()
        ));
    }
}