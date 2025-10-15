<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Http\Clients;

use AqHub\Player\Domain\ValueObjects\Name;
use AqHub\Core\Result;
use DomainException;
use GuzzleHttp\Client;

class AqwApiClient
{
    public const string URL = 'https://game.aq.com';

    public static function login(Name $name, string $password)
    {
        try {
            $response = (new Client())->post(self::URL . '/game/api/login/now', [
                'form_params' => [
                    'user' => $name->value,
                    'pass' => $password,
                    'option' => 1
                ]
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            // [ TODO ] - PARSER TO AN OBJECT
            if (!isset($body['login']) && !isset($body['login']['sToken'])) {
                throw new DomainException('Login endpoint dont returned token.');
            }

            return $body['login']['sToken'];
        } catch (\Throwable $th) {
            return Result::error($th->getMessage(), null);
        }
    }
}
