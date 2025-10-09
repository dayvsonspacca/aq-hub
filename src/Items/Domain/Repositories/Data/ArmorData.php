<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories\Data;

use AqHub\Items\Domain\ValueObjects\{Description, ItemTags, Name};
use AqHub\Shared\Domain\ValueObjects\StringIdentifier;
use DateTime;

class ArmorData
{
    public function __construct(
        public readonly StringIdentifier $identifier,
        public readonly Name $name,
        public readonly Description $description,
        public readonly ItemTags $tags,
        public readonly DateTime $registeredAt
    ) {}

    public function toArray()
    {
        return [
            'id' => $this->identifier->getValue(),
            'name' => $this->name->value,
            'description' => $this->description->value,
            'registered_at' => $this->registeredAt->format('Y-m-d H:i:s'),
            'tags' => $this->tags->toArray()
        ];
    }
}
