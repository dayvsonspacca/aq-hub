<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Http\Services;

use AqHub\Core\Env;
use AqHub\Core\Result;
use Exception;
use Firebase\JWT\{JWT, Key};
use RuntimeException;

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

    public function sign(array $payload, int $expiresIn = 3600): string
    {
        $payload['iss'] = 'aqhub-api';
        $payload['aud'] = 'aqhub-client';
        $payload['iat'] = time();
        $payload['exp'] = time() + $expiresIn;

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    /** @return Result<array> */
    public function validate(string $token): Result
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));
            return Result::success(null, (array) $decoded);
        } catch (Exception $e) {
            return Result::error($e->getMessage(), null);
        }
    }
}
