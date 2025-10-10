<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Adapters\AqwSocketClient;

use AqHub\Player\Domain\ValueObjects\Name;
use AqHub\Player\Infrastructure\Adapters\AqwSocketClient\Events\PlayerFoundEvent;
use AqwSocketClient\Events\Handlers\EventsHandlerInterface;
use AqwSocketClient\Server;
use Symfony\Component\Console\Output\OutputInterface;

class LogEventsHandler implements EventsHandlerInterface
{
    public function __construct(
        private readonly OutputInterface $output,
        private readonly Name $userName,
        private readonly Server $server
    ) {
    }

    public function handle(array $events): array
    {
        foreach ($events as $event) {
            if ($event instanceof PlayerFoundEvent) {
                $logMessage = sprintf(
                    '<fg=yellow;options=bold>[%s]</> <fg=green;options=bold>%s</> find player: <fg=cyan;options=bold>%s</>',
                    $this->server->name,
                    $this->userName->value,
                    $event->name->value
                );

                $this->output->writeln($logMessage);
            }
        }

        return [];
    }
}
