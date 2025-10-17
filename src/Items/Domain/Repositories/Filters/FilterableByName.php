<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories\Filters;

use AqHub\Items\Domain\ValueObjects\Name;

trait FilterableByName
{
    public ?Name $name = null;

    public function setName(Name $name)
    {
        $this->name = $name;
    }
}
