<?php

declare(strict_types=1);

namespace AqWiki\Domain\Abstractions;

use AqWiki\Domain\{ValueObjects, Abstractions, Exceptions};

abstract class AqwItem extends Abstractions\Entity
{
    private string $name;
    private ?ValueObjects\GameCurrency $price = null;
    private ValueObjects\GameCurrency $sellback;
    private string $description;
    private ValueObjects\ItemTags $tags;

    public function __construct()
    {
        $this->tags = new ValueObjects\ItemTags();
    }

    public function defineName(string $name): self
    {
        if (empty(trim($name))) {
            throw Exceptions\AqwItemException::invalidAttribute('The name of an item can not be empty.');
        }
        $this->name = trim($name);
        return $this;
    }

    public function definePrice(ValueObjects\GameCurrency $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function defineSellback(ValueObjects\GameCurrency $sellback): self
    {
        $this->sellback = $sellback;
        return $this;
    }

    public function defineDescription(string $description): self
    {
        if (empty($description)) {
            throw Exceptions\AqwItemException::invalidAttribute('The description of an item can not be empty.');
        }
        $this->description = $description;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): ?ValueObjects\GameCurrency
    {
        return $this->price;
    }

    public function getSellback(): ValueObjects\GameCurrency
    {
        return $this->sellback;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTags(): ValueObjects\ItemTags
    {
        return $this->tags;
    }
}
