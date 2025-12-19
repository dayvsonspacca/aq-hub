<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Repositories\Pgsql;

use AqHub\Core\Infrastructure\Database\PgsqlConnection;
use Aura\SqlQuery\QueryFactory;
use PDO;

class PgsqlUsersApiRepository
{
    public function __construct(
        private readonly PgsqlConnection $db,
        private readonly QueryFactory $query
    ) {}

    public function exists(string $username, string $password): bool
    {
        $select = $this->query->newSelect();

        $select->from('users_api')
            ->cols(['*'])
            ->where('username = :username', ['username' => $username])
            ->where('password = :password', ['password' => $password]);

        $statement = $this->db->connection->prepare($select->getStatement());
        $statement->execute($select->getBindValues());
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        return true;
    }
}
