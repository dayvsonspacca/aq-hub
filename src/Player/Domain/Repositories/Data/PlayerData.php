<?php

declare(strict_types=1);

namespace AqHub\Player\Domain\Repositories\Data;

use AqHub\Player\Domain\Entities\Player;
use AqHub\Player\Domain\ValueObjects\{Level, Name};
use AqHub\Shared\Domain\ValueObjects\IntIdentifier;
use DateTime;

class PlayerData
{
    public function __construct(
        public readonly IntIdentifier $identifier,
        public readonly Name $name,
        public readonly Level $level,
        public readonly DateTime $registeredAt,
        public readonly bool $mined
    ) {
    }

    public static function fromDomain(Player $player, DateTime $registeredAt, bool $mined)
    {
        return new self(
            IntIdentifier::create($player->getId())->unwrap(),
            Name::create($player->getName())->unwrap(),
            Level::create($player->getLevel())->unwrap(),
            $registeredAt,
            $mined
        );
    }

    public function toArray()
    {
        return [
            'id' => $this->identifier->getValue(),
            'name' => $this->name->value,
            'level' => $this->level->value,
            'created_at' => $this->registeredAt->format('Y-m-d H:i:s'),
            'mined' => $this->mined
        ];
    }
}
