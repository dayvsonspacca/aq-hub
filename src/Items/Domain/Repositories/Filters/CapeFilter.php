<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories\Filters;

class CapeFilter
{
    use DefaultFilters;

    public bool $canAccessBank = false;

    public function setCanAccessBank(bool $can)
    {
        $this->canAccessBank = $can;
    }

    public function toArray(): array
    {
        return array_merge(
            $this->defaultsArray(),
            [
                'can_access_bank' => $this->canAccessBank,
            ]
        );
    }

    public function generateUniqueKey(): string
    {
        $key = $this->defaultsUniqueKey();

        $key .= '_can_access_bank-' . $this->canAccessBank;

        return md5($key);
    }
}
