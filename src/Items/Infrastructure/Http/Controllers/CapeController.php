<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Controllers;

use AqHub\Items\Application\UseCases\Cape\CapeUseCases;
use AqHub\Items\Infrastructure\Http\Forms\FindCapesForm;
use AqHub\Items\Infrastructure\Http\Presenters\CapePresenter;
use AqHub\Shared\Infrastructure\Http\Route;
use RuntimeException;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};

class CapeController
{
    public function __construct(
        private readonly CapeUseCases $capeUseCases
    ) {
    }

    #[Route(path: '/capes/list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $result = FindCapesForm::fromRequest($request);
        if ($result->isError()) {
            return new JsonResponse(['message' => $result->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $filter = $result->getData();
        $armors = $this->capeUseCases->findAll->execute($filter);

        if ($armors->isError()) {
            throw new RuntimeException($armors->getMessage());
        }

        $armors = $armors->getData();
        $armors = CapePresenter::array($armors);

        return new JsonResponse([
            'filter' => $filter->toArray(),
            'armors' => $armors
        ], Response::HTTP_OK);
    }
}
