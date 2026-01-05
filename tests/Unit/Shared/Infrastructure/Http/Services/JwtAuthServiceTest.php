<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Shared\Infrastructure\Http\Services;

use AqHub\Core\Env;
use AqHub\Shared\Infrastructure\Http\Services\JwtAuthService;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;

final class JwtAuthServiceTest extends TestCase
{
    private JwtAuthService $jwtAuthService;

    protected function setUp(): void
    {
        $this->jwtAuthService = new JwtAuthService(Env::load(['API_JWT_SECRET_TOKEN' => 'SUPER_SECRET_WAS_TOO_LOW_SO_I_INCRESEAD_A_BIT'], forceReload: true));
    }

    #[Test]
    public function should_fail_when_api_secret_token_not_in_env()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('API_JWT_SECRET_TOKEN not set.');

        new JwtAuthService(Env::load([], forceReload: true));
    }

    #[Test]
    public function should_sign_with_default_fields()
    {
        $token = $this->jwtAuthService->sign(['username' => 'Hilise']);

        $this->assertNotEmpty($token);

        $result = $this->jwtAuthService->validate($token);
        $this->assertTrue($result->isSuccess());

        $decoded = $result->unwrap();

        $this->assertArrayHasKey('iss', $decoded);
        $this->assertArrayHasKey('aud', $decoded);
        $this->assertArrayHasKey('iat', $decoded);
        $this->assertArrayHasKey('exp', $decoded);
        $this->assertArrayHasKey('username', $decoded);

        $this->assertCount(5, $decoded);

        $this->assertSame($decoded['aud'], 'aqhub-client');
        $this->assertSame($decoded['iss'], 'aqhub-api');
        $this->assertSame($decoded['username'], 'Hilise');
    }
}
