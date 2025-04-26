<?php

declare(strict_types=1);

namespace AqWiki\Domain\Utils;

class Strings
{
    public static function ifEmptyThrow(string $value, \Exception $exception): string
    {
        $value = trim($value);
        if (empty($value)) {
            throw $exception;
        }

        return $value;
    }
}
