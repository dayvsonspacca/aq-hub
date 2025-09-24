<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Services;

use AqHub\Shared\Domain\ValueObjects\{Result, IntIdentifier};
use AqHub\Player\Domain\ValueObjects\Name;
use GuzzleHttp\Client;
use DomainException;

class CharpageScrapper
{
    /**
     * @return Result<IntIdentifier|null>
     */
    public static function findIdentifier(Name $name): Result
    {
        try {
            $response = (new Client())->get('https://account.aq.com/CharPage?id=' . $name->value);
            $html =  (string) $response->getBody();

            preg_match("/var\s+ccid\s*=\s*(\d+);/", $html ?? '', $matches);
            if (!isset($matches[1])) {
                throw new DomainException('Could not found the ccid on page from charpage: '. $name->value);
            }

            $ccid = (int) $matches[1];

            return Result::success(null, IntIdentifier::create($ccid)->unwrap());
        } catch (\Throwable $th) {
            return Result::error($th->getMessage(), null);
        }

    }
}
