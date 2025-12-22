<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Controllers\Rest;

use AqHub\Core\Infrastructure\Http\Interfaces\RestController;
use AqHub\Core\Infrastructure\Http\Route;
use AqHub\Items\Application\Armors\Commands\Add;
use AqHub\Items\Application\Armors\Queries\FindAll;
use AqHub\Items\Infrastructure\Http\Forms\AddArmorForm;
use AqHub\Items\Infrastructure\Http\Forms\ListAllArmorsForm;
use AqHub\Items\Infrastructure\Http\OpenAPI\QueryParameters\{NameParameter, RaritiesParameter, TagsParamenter};
use AqHub\Items\Infrastructure\Http\OpenAPI\Response\ListArmorsResponse;
use AqHub\Shared\Domain\Helpers\ArrayPresenter;
use AqHub\Shared\Infrastructure\Http\Middlewares\JwtAuthMiddleware;
use AqHub\Shared\Infrastructure\Http\OpenAPI\QueryParameters\{PageParameter, PageSizeParameter};
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};

class ArmorController implements RestController
{
    public function __construct(
        private readonly FindAll $findAll,
        private readonly Add $add
    ) {}

    #[OA\Get(
        path: '/armors/list',
        summary: 'List armors',
        tags: ['Armors'],
        parameters: [new PageParameter(), new PageSizeParameter(), new NameParameter(), new RaritiesParameter(), new TagsParamenter()],
        responses: [new ListArmorsResponse()]
    )]
    #[Route(path: '/armors/list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $filter = ListAllArmorsForm::fromRequest($request);
        $output = $this->findAll->execute($filter);

        return new JsonResponse([
            'filter' => $filter->toArray(),
            'armors' => ArrayPresenter::presentCollection($output->armors),
            'total' => $output->total
        ], Response::HTTP_OK);
    }

    #[Route(path: '/armors/add', methods: ['POST'], middlewares: [JwtAuthMiddleware::class])]
    public function add(Request $request): JsonResponse
    {
        $input = AddArmorForm::fromRequest($request);
        if ($input->isError()) {
            return new JsonResponse([
                'message' => $input->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $itemInfo = $input->getData();

        $result = $this->add->execute($itemInfo);
        
        if ($result->isError()) {
            return new JsonResponse([
                'message' => $result->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new JsonResponse([
            'message' => $result->getMessage()
        ], Response::HTTP_CREATED);
    }
}
