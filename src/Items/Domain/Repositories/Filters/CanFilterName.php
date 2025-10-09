<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories\Filters;

use AqHub\Items\Domain\ValueObjects\Name;

trait CanFilterName
{
    public ?Name $name;

    public function setName(Name $name)
    {
        $this->name = $name;
    }
}