<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit;

use Symfony\Component\HttpFoundation\Request;

abstract class TestRequests extends TestCase
{
    protected function createRequest(array $query = []): Request
    {
        return new Request($query);
    }
}
