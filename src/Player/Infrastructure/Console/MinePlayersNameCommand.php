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
            )->addArgument(
                'server',
                InputArgument::REQUIRED,
                'The AQW server name (e.g., twilly, yorumi)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username   = $input->getArgument('username');
        $password   = $input->getArgument('password');
        $serverName = $input->getArgument('server');

        $server = $this->getServerByName($serverName);

        if ($server === null) {
            $output->writeln('<error>Invalid server: '. $serverName .'</error>');
            return Command::FAILURE;
        }

        $token = AqwApiClient::login(Name::create($username)->unwrap(), $password);

        $client = new Client(
            $server,
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

    private function getServerByName(string $name): ?Server
    {
        try {
            if (!method_exists(Server::class, $name)) {
                return null;
            }
            return call_user_func([Server::class, $name]);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
