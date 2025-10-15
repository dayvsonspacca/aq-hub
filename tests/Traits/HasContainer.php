<?php

declare(strict_types=1);

namespace AqHub\Tests\Traits;

use AqHub\Core\ContainerFactory;
use AqHub\Core\CoreDefinations;

trait HasContainer
{
    public function container()
    {
        return ContainerFactory::make(array_merge(
            CoreDefinations::dependencies()
        ));
    }
}