<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Adapters\AqwSocketClient;

use AqHub\Player\Domain\ValueObjects\Name;
use AqHub\Player\Infrastructure\Adapters\AqwSocketClient\Events\PlayerFoundEvent;
use AqwSocketClient\Events\Factories\EventsFactoryInterface;

class EventsFactory implements EventsFactoryInterface
{
    public function fromMessage(string $message): array
    {
        $events = [];
        preg_match("/%xt%uotls%-1%([^%]+)%/", $message, $matches);
        if (isset($matches[1])) {
            $name = Name::create($matches[1]);
            if ($name->isSuccess()) {
                $events[] = new PlayerFoundEvent($name->getData());
            }
        }

        return $events;
    }
}
