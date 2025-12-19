<?php

declare(strict_types=1);

namespace AqHub\Tests\DataProviders;

use AqHub\Items\Domain\Entities\Cape;
use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\Repositories\Data\CapeData;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Shared\Domain\Enums\ItemTag;
use AqHub\Shared\Domain\ValueObjects\StringIdentifier;
use DateTime;

class CapeDataProvider
{
    private ?StringIdentifier $identifier = null;
    private Name $name;
    private Description $description;
    private ItemTags $tags;
    private DateTime $registeredAt;
    private ?ItemRarity $rarity;
    private bool $canAccessBank;

    public function __construct()
    {
        $this->name          = Name::create('Default Cape')->unwrap();
        $this->description   = Description::create('A basic cape description.')->unwrap();
        $this->tags          = new ItemTags([ItemTag::AdventureCoins]);
        $this->registeredAt  = new DateTime('2025-10-16');
        $this->rarity        = ItemRarity::Awesome;
        $this->canAccessBank = false;
    }

    public static function make(): self
    {
        return new self();
    }

    public function withIdentifier(StringIdentifier $id): self
    {
        $this->identifier = $id;
        return $this;
    }

    public function withName(Name $name): self
    {
        $this->name       = $name;
        $this->identifier = null;
        return $this;
    }

    public function withDescription(Description $description): self
    {
        $this->description = $description;
        $this->identifier  = null;
        return $this;
    }

    public function withRarity(?ItemRarity $rarity): self
    {
        $this->rarity     = $rarity;
        $this->identifier = null;
        return $this;
    }

    /**
     * @param ItemTag[] $tags
     */
    public function withTags(array $tags): self
    {
        $this->tags       = new ItemTags($tags);
        $this->identifier = null;
        return $this;
    }

    public function withRegisteredAt(DateTime $registeredAt): self
    {
        $this->registeredAt = $registeredAt;
        return $this;
    }

    public function withCanAccessBank(bool $canAccessBank): self
    {
        $this->canAccessBank = $canAccessBank;
        return $this;
    }

    public function build(): CapeData
    {
        $itemInfo = ItemInfo::create($this->name, $this->description, $this->tags, $this->rarity)->unwrap();

        $identifier = $this->identifier
            ? $this->identifier
            : ItemIdentifierGenerator::generate($itemInfo, Cape::class)->unwrap();

        return new CapeData(
            identifier: $identifier,
            name: $this->name,
            description: $this->description,
            tags: $this->tags,
            registeredAt: $this->registeredAt,
            rarity: $this->rarity,
            canAccessBank: $this->canAccessBank
        );
    }

    public function buildCollection(int $count = 3): array
    {
        $collection = [];

        for ($i = 1; $i <= $count; $i++) {
            $collection[] = self::make()
                ->withName(Name::create("Test Cape Collection #{$i}")->unwrap())
                ->withDescription(Description::create("Description for cape #{$i}")->unwrap())
                ->build();
        }

        return $collection;
    }
}
