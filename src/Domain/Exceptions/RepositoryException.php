<?php

declare(strict_types=1);

namespace AqWiki\Domain\Exceptions;

use Exception;

final class RepositoryException extends Exception
{
    public static function alreadyExists(string $repository)
    {
        return new self('An record with same id already exists, repository: ' . $repository);
    }
}
