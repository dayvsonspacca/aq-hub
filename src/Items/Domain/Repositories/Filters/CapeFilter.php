<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories\Filters;

class CapeFilter
{
    use DefaultFilters;

    public ?bool $canAccessBank = null;

    public function setCanAccessBank(bool $can)
    {
        $this->canAccessBank = $can;
    }

    public function toArray(): array
    {
        return array_merge(
            $this->defaultsArray(),
            [
                'can_access_bank' => isset($this->canAccessBank) ? $this->canAccessBank : null,
            ]
        );
    }

    public function generateUniqueKey(): string
    {
        $key = $this->defaultsUniqueKey();

        if (!is_null($this->canAccessBank)) {
            $key .= '_can_access_bank-' . ($this->canAccessBank ? 'true' : 'false');
        }

        return md5($key);
    }
}
