<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Adapters\AqwSocketClient;

use AqHub\Player\Infrastructure\Adapters\AqwSocketClient\Events\PlayerFoundEvent;
use AqwSocketClient\Events\Handlers\EventsHandlerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LogEventsHandler implements EventsHandlerInterface
{
    public function __construct(private readonly OutputInterface $output) {}

    public function handle(array $events): array
    {
        foreach ($events as $event) {
            if ($event instanceof PlayerFoundEvent) {
                $this->output->writeln("<fg=magenta;options=bold>▶ Player found:</> <fg=cyan>{$event->name->value}</>");
            }
        }

        return [];
    }
}
