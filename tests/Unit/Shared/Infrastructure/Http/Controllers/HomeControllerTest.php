<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Shared\Infrastructure\Http\Controllers\Rest;

use AqHub\Shared\Infrastructure\Http\Controllers\HomeController;
use AqHub\Tests\TestCase;
use AqHub\Tests\Traits\DoRequests;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\RedirectResponse;

class HomeControllerTest extends TestCase
{
    use DoRequests;

    #[Test]
    public function should_redirect_to_api_docs_page()
    {
        $request = $this->makeRequest();
        $controller = new HomeController();

        $response = $controller->home($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame($response->getTargetUrl(), '/api-docs.html');
    }
}
