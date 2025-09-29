<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Abstractions;

use AqHub\Items\Domain\ValueObjects\{ItemInfo, ItemTags};
use AqHub\Shared\Domain\Abstractions\Entity;

abstract class AqwItem extends Entity
{
    protected ItemInfo $info;

    public function getName(): string
    {
        return $this->info->getName();
    }

    public function getDescription(): string
    {
        return $this->info->getDescription();
    }

    public function getTags(): ItemTags
    {
        return $this->info->tags;
    }
}
