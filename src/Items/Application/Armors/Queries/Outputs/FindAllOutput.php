<?php

declare(strict_types=1);

namespace AqHub\Items\Application\Armors\Queries\Outputs;

use AqHub\Items\Domain\Repositories\Data\ArmorData;

class FindAllOutput
{
    /** @param ArmorData[] $armors */
    public function __construct(
        public readonly array $armors,
        public readonly int $total
    ) {
    }
}
