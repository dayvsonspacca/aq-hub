<?php

declare(strict_types=1);

namespace AqHub\Core\Infrastructure\Http;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Route
{
    public function __construct(
        public string $path,
        public array $methods
    ) {
    }
}
