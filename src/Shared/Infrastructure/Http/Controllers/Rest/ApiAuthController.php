<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Http\Controllers\Rest;

use AqHub\Core\Infrastructure\Http\Interfaces\RestController;
use AqHub\Core\Infrastructure\Http\Route;
use AqHub\Shared\Infrastructure\Http\Services\JwtAuthService;
use AqHub\Shared\Infrastructure\Repositories\Pgsql\PgsqlUsersApiRepository;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};

class ApiAuthController implements RestController
{
    public function __construct(
        private readonly JwtAuthService $authService,
        private readonly PgsqlUsersApiRepository $usersApiRepository
    ) {
    }

    #[Route('/auth/login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $payload = $request->toArray();

        $username = $payload['username'] ?? '';
        $password = $payload['password'] ?? '';

        $userExists = $this->usersApiRepository->exists($username, $password);

        if (!$userExists) {
            return new JsonResponse(['message' => 'User not found.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $token = $this->authService->sign([
            'username' => $username
        ]);

        if ($token->isError()) {
            return new JsonResponse(['message' => $token->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $token = $token->getData();

        return new JsonResponse(['token' => $token], Response::HTTP_OK);
    }
}
