<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Services;

use AqHub\Items\Domain\ValueObjects\{Description, ItemTags, Name};
use AqHub\Items\Infrastructure\Data\ItemData;
use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Shared\Domain\ValueObjects\Result;
use GuzzleHttp\Client;

class AqWikiScrapper
{
    /**
     * @return Result<ItemData|null>
     */
    public static function findItemData(Name $name)
    {
        try {
            $urls     = self::generatePossibleUrls($name);
            $itemTags = new ItemTags();

            foreach ($urls as $url) {
                try {
                    $response = (new Client())->get('http://aqwwiki.wikidot.com/' . $url);
                    $html     =  (string) $response->getBody();

                    preg_match('/<strong>\s*Description:\s*<\/strong>\s*(.*?)(?:<br\s*\/?>|<\/|\z)/is', $html, $matches);
                    if (!isset($matches[1])) {
                        continue;
                    }

                    $description = Description::create(html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'))->unwrap();

                    preg_match_all('/\/image-tags\/(ac|rare|pseudo|legend|special|seasonal)large\.png/i', $html, $tagMatches);
                    if (!empty($tagMatches[1])) {
                        foreach ($tagMatches[1] as $tagString) {
                            $result = TagType::fromString($tagString);
                            if ($result->isSuccess()) {
                                $itemTags->add($result->getData());
                            }
                        }
                    }

                    break;
                } catch (\Throwable) {
                }
            }

            if (is_null($description ?? null)) {
                return Result::error('Description not found for: ' . $name->value, null);
            }

            return Result::success(null, new ItemData($name, $description, $itemTags));
        } catch (\Throwable $th) {
            return Result::error($th->getMessage(), null);
        }
    }

    private static function generatePossibleUrls(Name $name)
    {
        $urls   = [];

        $urls[] = $name->value;
        $urls[] = $name->value . '-ac';
        $urls[] = $name->value . '-0-ac';
        $urls[] = $name->value . '-non-ac';
        $urls[] = $name->value . '-legend';
        $urls[] = $name->value . '-non-legend';

        return $urls;
    }
}
