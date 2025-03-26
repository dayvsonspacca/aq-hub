<?php

declare(strict_types=1);

namespace AqWiki\Infrastructure\Repositories\PDOSqlite;

use AqWiki\Domain\{Entities, Repositories, Exceptions};

final class PDOSqliteMiscItemRepository implements Repositories\MiscItemRepositoryInterface
{
    private \PDO $database;

    public function __construct()
    {
        $this->database = new \PDO('sqlite:database.sqlite');
    }

    public function persist(Entities\MiscItem $miscItem)
    {
        if ($this->findByName($miscItem->getName())) {
            throw Exceptions\RepositoryException::alreadyExists(__CLASS__);
        }

        $statement = $this->database->prepare(
            'INSERT INTO misc_items (name, description, price, sellback) VALUES (:name, :description, :price, :sellback);'
        );

        $statement->execute([
           'name' => $miscItem->getName(),
           'description' => $miscItem->getDescription(),
           'price' => $miscItem->getPrice() ? serialize($miscItem->getPrice()) : null,
           'sellback' => serialize($miscItem->getSellback())
        ]);
    }

    public function findByName(string $name): ?Entities\MiscItem
    {
        $statement = $this->database->prepare(
            'SELECT * FROM misc_items WHERE name = :name;'
        );
        $statement->execute([
            'name' => $name
        ]);

        $record = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$record) {
            return null;
        }

        $miscItem = new Entities\MiscItem();
        $miscItem
            ->defineName($record['name'])
            ->defineDescription($record['description'])
            ->defineSellback(unserialize($record['sellback']));

        if ($record['price']) {
            $miscItem->definePrice(unserialize($record['price']));
        }

        return $miscItem;
    }
}
