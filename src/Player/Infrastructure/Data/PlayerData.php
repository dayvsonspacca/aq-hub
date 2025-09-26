<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Data;

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
        public readonly DateTime $createdAt,
        public readonly bool $mined
    ) {}

    public static function fromDomain(Player $player, DateTime $createdAt, bool $mined)
    {
        return new self(
            IntIdentifier::create($player->getId())->unwrap(),
            Name::create($player->getName())->unwrap(),
            Level::create($player->getLevel())->unwrap(),
            $createdAt,
            $mined
        );
    }
}
