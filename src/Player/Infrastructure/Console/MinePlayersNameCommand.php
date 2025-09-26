<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Console;

use AqHub\Player\Application\UseCases\AddPlayer;
use AqHub\Player\Domain\ValueObjects\Name;
use AqHub\Player\Infrastructure\Adapters\AqwSocketClient\{EventsFactory, EventsHandler, LogEventsHandler};
use AqHub\Shared\Infrastructure\Http\Clients\AqwApiClient;
use AqwSocketClient\{Client, Server};
use AqwSocketClient\Events\Factories\CoreEventsFactory;
use AqwSocketClient\Events\Handlers\CoreEventsHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputArgument, InputInterface};
use Symfony\Component\Console\Output\OutputInterface;

class MinePlayersNameCommand extends Command
{
    public function __construct(
        private readonly AddPlayer $addPlayer
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('player:mine-players-name')
            ->setDescription('Mine items from all players in the repository and persist in database')
            ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'The AQW user nickname'
            )
            ->addArgument(
                'password',
                InputArgument::REQUIRED,
                'The account password'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        $token = AqwApiClient::login(Name::create($username)->unwrap(), $password);

        $client = new Client(
            Server::yorumi(),
            [
                new CoreEventsFactory(),
                new EventsFactory()
            ],
            [
                new CoreEventsHandler($username, $token),
                new EventsHandler($this->addPlayer),
                new LogEventsHandler($output)
            ]
        );
        $client->run();

        return Command::SUCCESS;
    }
}
