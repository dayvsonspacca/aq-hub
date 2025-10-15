<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Scrappers;

use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\ValueObjects\{Description, ItemTags, Name};
use AqHub\Items\Infrastructure\Data\WikiItemData;
use AqHub\Shared\Domain\Enums\ItemTag;
use AqHub\Core\Result;
use GuzzleHttp\Client;

class AqWikiScrapper
{
    /**
     * @return Result<WikiItemData|null>
     */
    public static function findItemData(Name $name)
    {
        try {
            $urls     = self::generatePossibleUrls($name);
            $itemTags = new ItemTags();

            foreach ($urls as $url) {
                try {
                    $response = (new Client())->get('http://aqwwiki.wikidot.com/' . $url);
                    $html     = (string) $response->getBody();

                    preg_match('/<strong>\s*Description:\s*<\/strong>\s*(.*?)(?:<br\s*\/?>|<\/|\z)/is', $html, $matches);
                    if (!isset($matches[1])) {
                        continue;
                    }

                    $description = Description::create(strip_tags(html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8')))->unwrap();

                    preg_match_all('/\/image-tags\/(ac|rare|pseudo|legend|special|seasonal)large\.png/i', $html, $tagMatches);
                    if (!empty($tagMatches[1])) {
                        foreach ($tagMatches[1] as $tagString) {
                            $result = ItemTag::fromString($tagString);
                            if ($result->isSuccess()) {
                                $itemTags->add($result->getData());
                            }
                        }
                    }

                    preg_match('/<strong>\s*Rarity:\s*<\/strong>\s*(.*?)(?:<br\s*\/?>|<\/|\z)/is', $html, $matches);
                    if (!isset($matches[1])) {
                        continue;
                    }

                    $rarity = ItemRarity::fromString($matches[1])->unwrap();

                    $canAccessBank = null;

                    if (preg_match('/<div[^>]*id=["\']breadcrumbs["\'][^>]*>(.*?)<\/div>/is', $html, $breadcrumbMatch)) {
                        if (stripos($breadcrumbMatch[1], 'Capes &amp; Back Items') !== false) {
                            if (stripos($html, 'Opens the bank for <strong>only</strong> the owner when clicked on.') !== false) {
                                $canAccessBank = true;
                            } else {
                                $canAccessBank = false;
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

            return Result::success(null, new WikiItemData(
                $name,
                $description,
                $itemTags,
                $rarity ?? null,
                $canAccessBank ?? null
            ));
        } catch (\Throwable $th) {
            return Result::error($th->getMessage(), null);
        }
    }

    private static function generatePossibleUrls(Name $name)
    {
        $urls = [];

        $slug = strtolower($name->value);
        $slug = preg_replace('/[^\p{L}\p{N}\s\'-]+/u', '', $slug);
        $slug = str_replace([' ', '\''], '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        $urls[] = $slug;
        $urls[] = $slug . '-ac';
        $urls[] = $slug . '-0-ac';
        $urls[] = $slug . '-non-ac';
        $urls[] = $slug . '-legend';
        $urls[] = $slug . '-non-legend';

        return $urls;
    }
}
