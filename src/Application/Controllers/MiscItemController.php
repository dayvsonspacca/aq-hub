<?php

declare(strict_types=1);

namespace AqWiki\Application\Controllers;

use AqWiki\Infrastructure\Repositories\PDOSqlite\PDOSqliteMiscItemRepository;
use Symfony\Component\HttpFoundation\{Response, Request};
use AqWiki\Domain\{Entities, ValueObjects, Enums};
use AqWiki\Application\UseCases\MiscItem\Persist;

final class MiscItemController
{
    public function add(Request $request): Response
    {
        $response = new Response();
        try {
            $body = json_decode($request->getContent(), true);

            $miscItem = new Entities\MiscItem();
            $miscItem
                ->defineName($body['name'] ?? '')
                ->defineDescription($body['description'] ?? '')
                ->defineSellback(new ValueObjects\GameCurrency($body['sellback']['value'], Enums\CurrencyType::cases()[$body['sellback']['type']]));

            if (!empty($body['price'])) {
                $miscItem->definePrice(
                    new ValueObjects\GameCurrency($body['price']['value'], Enums\CurrencyType::cases()[$body['price']['type']])
                );
            }

            $handler = new Persist\Handler(new PDOSqliteMiscItemRepository());
            $handler->handle($miscItem);

            return $response;
        } catch (\Throwable $th) {
            $response->setStatusCode(500);
            $response->setContent(
                json_encode([
                    'message' => $th->getMessage() ?? 'INTERNAL SERVER ERROR',
                    'code' => 500
                ])
            );
        } finally {
            return $response;
        }
    }
}
