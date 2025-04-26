<?php

declare(strict_types=1);

namespace AqWiki\Domain\Abstractions;

use AqWiki\Domain\{ValueObjects, Abstractions, Exceptions, Utils};

abstract class AqwItem extends Abstractions\Entity
{
    private readonly string $name;
    private readonly string $description;
    private readonly ValueObjects\GameCurrency $price;
    private readonly ValueObjects\GameCurrency $sellback;
    private readonly ValueObjects\ItemTags $tags;

    public function __construct(
        string $name,
        string $description,
        ValueObjects\GameCurrency $price,
        ValueObjects\GameCurrency $sellback,
        ValueObjects\ItemTags $tags
    ) {
        $this->name = Utils\Strings::ifEmptyThrow($name, Exceptions\AqwItemException::invalidAttribute('The name of an item can not be empty.'));
        $this->description = Utils\Strings::ifEmptyThrow($description, Exceptions\AqwItemException::invalidAttribute('The name of an item can not be empty.'));

        $this->price = $price;
        $this->sellback = $sellback;
        $this->tags = $tags;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): ValueObjects\GameCurrency
    {
        return $this->price;
    }

    public function getSellback(): ValueObjects\GameCurrency
    {
        return $this->sellback;
    }

    public function getTags(): ValueObjects\ItemTags
    {
        return $this->tags;
    }
}
