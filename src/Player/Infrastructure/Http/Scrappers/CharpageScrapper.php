<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Http\Scrappers;

use AqHub\Items\Domain\ValueObjects\Name as ItemName;
use AqHub\Player\Domain\ValueObjects\{Level, Name};
use AqHub\Player\Infrastructure\Data\PlayerData;
use AqHub\Shared\Domain\ValueObjects\{IntIdentifier, Result};
use DomainException;
use GuzzleHttp\Client;

class CharpageScrapper
{
    /**
     * @return Result<PlayerData|null>
     */
    public static function findPlayerData(Name $name): Result
    {
        try {
            $response = (new Client())->get('https://account.aq.com/CharPage?id=' . $name->value);
            $html     =  (string) $response->getBody();

            preg_match("/var\s+ccid\s*=\s*(\d+);/", $html ?? '', $matches);
            if (!isset($matches[1])) {
                throw new DomainException('Could not found the ccid on player charpage: ' . $name->value);
            }

            $ccid       = (int) $matches[1];
            $identifier = IntIdentifier::create($ccid)->unwrap();

            preg_match('/<label>\s*Level:\s*<\/label>\s*(\d+)/i', $html, $matches);
            if (!isset($matches[1])) {
                throw new DomainException('Could not find the level on player charpage: ' . $name->value);
            }

            $level = Level::create((int) $matches[1])->unwrap();

            return Result::success(null, new PlayerData($identifier, $name, $level));
        } catch (\Throwable $th) {
            return Result::error($th->getMessage(), null);
        }
    }

    /**
     * Get all items names for a player, grouped by type.
     *
     * @param IntIdentifier $identifier
     * @return Result<array<string, ItemName[]>|null>  // chave: tipo do item, valor: array de ItemName
     */
    public static function findPlayerItemsNameByType(IntIdentifier $identifier): Result
    {
        try {
            $response = (new Client())->get('https://account.aq.com/CharPage/Inventory?ccid=' . $identifier->getValue());
            $jsonData = json_decode($response->getBody()->getContents(), true);

            $jsonData = array_filter($jsonData, fn ($obj) => $obj['strName'] !== 'Inventory Hidden');

            $itemsByType = [];

            foreach ($jsonData as $obj) {
                $nameResult = ItemName::create($obj['strName']);
                if ($nameResult->isSuccess()) {
                    $type                 = $obj['strType'] ?? 'Unknown';
                    $itemsByType[$type][] = $nameResult->unwrap();
                }
            }

            return Result::success(null, $itemsByType);
        } catch (\Throwable $th) {
            return Result::error($th->getMessage(), null);
        }
    }
}
