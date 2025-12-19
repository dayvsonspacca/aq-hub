<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Controllers\Rest;

use AqHub\Core\Infrastructure\Http\Interfaces\RestController;
use AqHub\Core\Infrastructure\Http\Route;
use AqHub\Items\Application\Capes\Queries\FindAll;
use AqHub\Items\Infrastructure\Http\Forms\ListAllCapesForm;
use AqHub\Shared\Domain\Helpers\ArrayPresenter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CapeController implements RestController
{
    public function __construct(private readonly FindAll $findAll)
    {
    }

    #[Route(path: '/capes/list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $filter = ListAllCapesForm::fromRequest($request);
        $output = $this->findAll->execute($filter);

        return new JsonResponse([
            'filter' => $filter->toArray(),
            'capes' => ArrayPresenter::presentCollection($output->capes),
            'total' => $output->total
        ], Response::HTTP_OK);
    }
}