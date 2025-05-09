<?php

declare(strict_types=1);

namespace AqWiki\Items\Domain\Abstractions;

use AqWiki\Items\Domain\ValueObjects\ItemInfo;
use AqWiki\Items\Domain\ValueObjects\ItemTags;
use AqWiki\Shared\Domain\Abstractions\Entity;

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
        return $this->info->getTags();
    }
}
