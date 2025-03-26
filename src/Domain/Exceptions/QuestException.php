<?php

declare(strict_types=1);

namespace AqWiki\Domain\Exceptions;

use Exception;

final class QuestException extends Exception
{
    public static function tooManyLevelRequirements(): self
    {
        return new self('A quest can not have 2 Level Requirements');
    }
}
