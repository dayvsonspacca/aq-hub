<?php

declare(strict_types=1);

namespace AqHub\Shared\Domain\Contracts;

interface Filter
{
    public function toArray(): array;
}