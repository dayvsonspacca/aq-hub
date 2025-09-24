<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Services;

use AqHub\Player\Domain\ValueObjects\Level;
use AqHub\Shared\Domain\ValueObjects\{Result, IntIdentifier};
use AqHub\Player\Domain\ValueObjects\Name;
use GuzzleHttp\Client;
use DomainException;

class CharpageScrapper
{
    /**
     * @return Result<array|null>
     */
    public static function findIdentifier(Name $name): Result
    {
        try {
            $response = (new Client())->get('https://account.aq.com/CharPage?id=' . $name->value);
            $html     =  (string) $response->getBody();

            preg_match("/var\s+ccid\s*=\s*(\d+);/", $html ?? '', $matches);
            if (!isset($matches[1])) {
                throw new DomainException('Could not found the ccid on player charpage: '. $name->value);
            }

            $ccid = (int) $matches[1];
            $identifier = IntIdentifier::create($ccid)->unwrap();

            preg_match('/<label>\s*Level:\s*<\/label>\s*(\d+)/i', $html, $matches);
            if (!isset($matches[1])) {
                throw new DomainException('Could not find the level on player charpage: '. $name->value);
            }
            
            $level = Level::create((int) $matches[1])->unwrap();

            return Result::success(null, [
                $identifier,
                $level
            ]);
        } catch (\Throwable $th) {
            return Result::error($th->getMessage(), null);
        }

    }
}
