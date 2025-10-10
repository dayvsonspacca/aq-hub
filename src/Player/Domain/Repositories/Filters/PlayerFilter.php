<?php

declare(strict_types=1);

namespace AqHub\Player\Domain\Repositories\Filters;

use AqHub\Shared\Domain\Repositories\Filters\CanPaginate;

class PlayerFilter
{
    use CanPaginate;

    public ?bool $mined = null;

    public function isMined(bool $mined)
    {
        $this->mined = $mined;
    }

    public function generateUniqueKey(): string
    {
        $key = 'page-' . $this->page;

        if (!is_null($this->mined)) {
            $key .= 'mined-' . $this->mined;
        }

        return md5($key);
    }

    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'page_size' => $this->pageSize,
            'mined' => $this->mined
        ];
    }
}
