<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Forms;

use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\Repositories\Filters\CapeFilter;
use AqHub\Items\Domain\ValueObjects\Name;
use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Shared\Domain\ValueObjects\Result;
use Symfony\Component\HttpFoundation\Request;

class FindCapesForm
{
    /**
     * @return Result<CapeFilter>
     */
    public static function fromRequest(Request $request): Result
    {
        $page = (int) $request->get('page', 1);

        if ($page <= 0) {
            return Result::error('Param page cannot be zero or negative.', null);
        }

        $filter = new CapeFilter();
        $filter->setPage($page);

        $rarities = $request->get('rarities', false);
        if ($rarities) {
            $rarities = explode(',', $rarities);

            sort($rarities);

            $rarities = array_map(
                fn ($rawRarity) => ItemRarity::fromString($rawRarity)->getData(),
                array_filter($rarities, fn ($rawRarity) => ItemRarity::fromString($rawRarity)->isSuccess())
            );

            $filter->setRarities($rarities);
        }

        $tags = $request->get('tags', false);
        if ($tags) {
            $tags = explode(',', $tags);
            sort($tags);

            $tags = array_map(
                fn ($rawTag) => TagType::fromString($rawTag)->getData(),
                array_filter($tags, fn ($rawTag) => TagType::fromString($rawTag)->isSuccess())
            );

            $filter->setTags($tags);
        }

        $name = $request->get('name', false);
        if ($name) {
            $name = Name::create($name);
            if ($name->isError()) {
                return Result::error($name->getMessage(), null);
            }

            $filter->setName($name->getData());
        }

        $canAccessBank = mb_strtolower($request->get('can_access_bank', ''));
        if ($canAccessBank === 'yes' || $canAccessBank === 'no') {
            $filter->canAccessBank = $canAccessBank === 'yes' ? true : false;
        }

        return Result::success(null, $filter);
    }
}
