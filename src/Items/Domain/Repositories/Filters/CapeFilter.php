<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories\Filters;

use AqHub\Shared\Domain\Abstractions\Filter;

class CapeFilter extends Filter
{
    use HasDefaultFilters;

    public ?bool $canAccessBank = null;

    public function setCanAccessBank(bool $can)
    {
        $this->canAccessBank = $can;
    }

    public function toArray(): array
    {
        return array_merge($this->defaultsArray(), [
            'can_access_bank' => $this->canAccessBank
        ]);
    }

    public function generateUniqueKey(): string
    {
        return md5(json_encode($this->toArray()));;
    }
}
