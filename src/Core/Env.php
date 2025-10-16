<?php

declare(strict_types=1);

namespace AqHub\Core;

class Env
{
    private static ?self $instance = null;

    private function __construct(public readonly array $vars)
    {
    }

    public static function load(array $vars): self
    {
        if (self::$instance === null) {
            self::$instance = new self($vars);
        }

        return self::$instance;
    }
}
