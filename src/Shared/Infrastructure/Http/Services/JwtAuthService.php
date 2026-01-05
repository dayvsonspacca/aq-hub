<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Http\Services;

use AqHub\Core\Env;
use AqHub\Core\Result;
use Firebase\JWT\{JWT, Key};
use RuntimeException;
use Throwable;

class JwtAuthService
{
    private $secret;

    public function __construct(private Env $env)
    {
        if (!isset($this->env->vars['API_JWT_SECRET_TOKEN'])) {
            throw new RuntimeException('API_JWT_SECRET_TOKEN not set.');
        }

        $this->secret = $this->env->vars['API_JWT_SECRET_TOKEN'];
    }

    /** @return Result<string> */
    public function sign(array $payload, int $expiresIn = 3600): Result
    {
        $payload['iss'] = 'aqhub-api';
        $payload['aud'] = 'aqhub-client';
        $payload['iat'] = time();
        $payload['exp'] = time() + $expiresIn;

        try {
            $token = JWT::encode($payload, $this->secret, 'HS256');
            return Result::success(null, $token);
        } catch (Throwable $th) {
            return Result::error($th->getMessage(), null);
        }
    }

    /** @return Result<array> */
    public function validate(string $token): Result
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));
            return Result::success(null, (array) $decoded);
        } catch (Throwable $th) {
            return Result::error($th->getMessage(), null);
        }
    }
}
