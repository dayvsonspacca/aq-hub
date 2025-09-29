<?php

declare(strict_types=1);

namespace AqHub\Items\Application\UseCases\Helmet;

use AqHub\Items\Domain\Repositories\HelmetRepository;
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Shared\Domain\ValueObjects\Result;

class AddHelmet
{
    public function __construct(private readonly HelmetRepository $helmetRepository)
    {
    }

    public function execute(ItemInfo $itemInfo): Result
    {
        return $this->helmetRepository->persist($itemInfo);
    }
}
