<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Forms;

use AqHub\Core\Result;
use AqHub\Items\Domain\Repositories\Filters\ArmorFilter;
use AqHub\Items\Infrastructure\Http\Forms\Fields\{NameField, RaritiesField, TagsField};
use AqHub\Shared\Infrastructure\Http\Forms\Fields\PageField;
use Symfony\Component\HttpFoundation\Request;

class FindArmorsForm
{
    /**
     * @return Result<ArmorFilter>
     */
    public static function fromRequest(Request $request): Result
    {
        try {
            $filter = new ArmorFilter();

            $filter->setPage(PageField::fromRequest($request));
            $filter->setRarities(RaritiesField::fromRequest($request));
            $filter->setTags(TagsField::fromRequest($request));

            $name = NameField::fromRequest($request);
            if ($name) {
                $filter->setName($name);
            }

            return Result::success(null, $filter);
        } catch (\Throwable $e) {
            return Result::error($e->getMessage(), null);
        }
    }
}
