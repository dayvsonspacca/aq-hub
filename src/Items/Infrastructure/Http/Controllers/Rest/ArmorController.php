<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Controllers\Rest;

use AqHub\Core\Infrastructure\Http\Interfaces\RestController;
use AqHub\Core\Infrastructure\Http\Route;
use AqHub\Items\Application\Armors\Queries\FindAll;
use AqHub\Items\Infrastructure\Http\Forms\ListAllArmorsForm;
use AqHub\Shared\Domain\Helpers\ArrayPresenter;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};

class ArmorController implements RestController
{
    public function __construct(private readonly FindAll $findAll)
    {
    }

    #[Route(path: '/armors/list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $filter = ListAllArmorsForm::fromRequest($request);
        $armors = $this->findAll->execute($filter);

        return new JsonResponse(ArrayPresenter::presentCollection($armors), Response::HTTP_OK);
    }
}
