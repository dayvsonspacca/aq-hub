<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Container;

use AqHub\Player\Infrastructure\Container\PlayerContainerRegistry;
use AqHub\Items\Infrastructure\Container\ItemsContainerRegistry;
use AqHub\Items\Infrastructure\Container\ItemsDefinations;
use AqHub\Player\Infrastructure\Container\PlayerDefinations;
use AqHub\Shared\Infrastructure\Database\Connection;
use AqHub\Shared\Infrastructure\Http\Application;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

use function DI\autowire;

class Container
{
    public static function build(): ContainerInterface
    {
        $builder = new ContainerBuilder();
        $builder->useAutowiring(true);
        $builder->addDefinitions(self::getDefinitions());
        
        return $builder->build();
    }

    private static function getDefinitions(): array
    {
        return array_merge(
            [
                Application::class => autowire(),
                Connection::class => function () {
                    $db = Connection::connect(
                        host: 'db',
                        dbname: 'aqhub',
                        username: 'aqhub',
                        password: 'aqhub',
                        port: 5432
                    );

                    if ($db->isError()) {
                        echo '[DB ERROR] ' . $db->getMessage() . PHP_EOL;
                        exit(1);
                    }

                    return $db->getData();
                },

                LoggerInterface::class => function (): Logger {
                    $logger = new Logger('aqhub_app');
                    $logger->pushHandler(new StreamHandler(LOGS_PATH . '/errors.log', Level::Warning));
                    return $logger;
                },

            ],
            ItemsDefinations::getDefinitions(),
            PlayerDefinations::getDefinitions()
        );
    }
}