<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Forms;

use AqHub\Items\Domain\Repositories\Filters\CapeFilter;
use AqHub\Items\Infrastructure\Http\Forms\Fields\NameField;
use AqHub\Items\Infrastructure\Http\Forms\Fields\RaritiesField;
use AqHub\Items\Infrastructure\Http\Forms\Fields\TagsField;
use AqHub\Shared\Domain\ValueObjects\Result;
use AqHub\Shared\Infrastructure\Http\Forms\Fields\PageField;
use Symfony\Component\HttpFoundation\Request;

class FindCapesForm
{
    /**
     * @return Result<CapeFilter>
     */
    public static function fromRequest(Request $request): Result
    {
        try {
            $filter = new CapeFilter();

            $filter->setPage(PageField::fromRequest($request));
            $filter->setRarities(RaritiesField::fromRequest($request));
            $filter->setTags(TagsField::fromRequest($request));

            $name = NameField::fromRequest($request);
            if ($name) {
                $filter->setName($name);
            }

            $canAccessBank = mb_strtolower($request->get('can_access_bank', ''));
            if ($canAccessBank === 'yes' || $canAccessBank === 'no') {
                $filter->canAccessBank = $canAccessBank === 'yes' ? true : false;
            }

            return Result::success(null, $filter);
        } catch (\Throwable $e) {
            return Result::error($e->getMessage(), null);
        }
    }
}
