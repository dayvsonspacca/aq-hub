<?php

declare(strict_types=1);

namespace AqHub\Core\Infrastructure\Http;

use AqHub\Core\Env;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use RuntimeException;
use Exception;

class JwtAuthService
{
    private string $secret;

    public function __construct(private Env $env)
    {
        if (!isset($this->env->vars['API_JWT_SECRET_TOKEN'])) {
            throw new RuntimeException('API_JWT_SECRET_TOKEN not set.');
        }

        $this->secret = $this->env->vars['API_JWT_SECRET_TOKEN'];
    }

    public function generateToken(array $payload, int $expiresIn = 3600): string
    {
        $payload['iat'] = time();
        $payload['exp'] = time() + $expiresIn;

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function validateToken(string $token): ?array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));
            return (array) $decoded;
        } catch (Exception) {
            return null;
        }
    }
}