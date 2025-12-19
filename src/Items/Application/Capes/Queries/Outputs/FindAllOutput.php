<?php

declare(strict_types=1);

namespace AqHub\Items\Application\Capes\Queries\Outputs;

use AqHub\Items\Domain\Repositories\Data\CapeData;

class FindAllOutput
{
    /** @param CapeData[] $capes */
    public function __construct(
        public readonly array $capes,
        public readonly int $total
    ) {
    }
}
