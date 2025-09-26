<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Commands;

use AqHub\Player\Application\UseCases\AddPlayer;
use AqHub\Player\Domain\ValueObjects\Name;
use AqHub\Shared\Infrastructure\Http\Clients\AqwApiClient;
use AqwSocketClient\Client;
use AqwSocketClient\Factories\CoreEventsFactory;
use AqwSocketClient\Factories\CoreEventsHandler;
use AqwSocketClient\Server;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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

        $client = new Client(Server::espada(), [new CoreEventsFactory()], [new CoreEventsHandler($username, $token)]);
        $client->run();

        return Command::SUCCESS;
    }
}
