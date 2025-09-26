<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Adapters\AqwSocketClient;

use AqHub\Player\Application\UseCases\AddPlayer;
use AqHub\Player\Infrastructure\Adapters\AqwSocketClient\Events\PlayerFoundEvent;
use AqwSocketClient\Events\Handlers\EventsHandlerInterface;

class EventsHandler implements EventsHandlerInterface
{
    public function __construct(private readonly AddPlayer $addPlayer)
    {
    }

    public function handle(array $events): array
    {
        foreach ($events as $event) {
            if ($event instanceof PlayerFoundEvent) {
                $this->addPlayer->execute($event->name);
            }
        }

        return [];
    }
}
