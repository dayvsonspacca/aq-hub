<?php

declare(strict_types=1);

namespace AqHub\Shared\Domain\Contracts;

use Closure;

interface Cache
{
    public function get(string $key, Closure $callback): mixed;

    public function invalidateTags(array $tags);
}