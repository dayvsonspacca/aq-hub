<?php

declare(strict_types=1);

namespace AqHub\Tests\Traits;

use Symfony\Component\HttpFoundation\Request;

trait DoRequests
{
    /**
     * Creates a new Symfony Request object for testing purposes.
     *
     * @param string $method The HTTP method (e.g., 'GET', 'POST', 'PUT').
     * @param string $uri The request URI (e.g., '/api/v1/items').
     * @param array $query Query string parameters (e.g., ['limit' => 10]).
     * @param array $content Request body data.
     * @return Request
     */
    public function makeRequest(
        string $method = 'GET',
        string $uri = '/',
        array $query = [],
        array $content = []
    ): Request {
        return Request::create(
            $uri,
            $method,
            $query,
            [],
            [],
            [],
            json_encode($content) // Content (para simular um corpo JSON)
        );
    }
}
