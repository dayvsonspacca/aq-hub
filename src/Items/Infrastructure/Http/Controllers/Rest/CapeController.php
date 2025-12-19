<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Controllers\Rest;

use AqHub\Core\Infrastructure\Http\Interfaces\RestController;
use AqHub\Core\Infrastructure\Http\Route;
use AqHub\Items\Application\Capes\Queries\FindAll;
use AqHub\Items\Infrastructure\Http\Forms\ListAllCapesForm;
use AqHub\Items\Infrastructure\Http\OpenAPI\QueryParameters\NameParameter;
use AqHub\Items\Infrastructure\Http\OpenAPI\QueryParameters\RaritiesParameter;
use AqHub\Items\Infrastructure\Http\OpenAPI\QueryParameters\TagsParamenter;
use AqHub\Items\Infrastructure\Http\OpenAPI\Response\ListCapesResponse;
use AqHub\Shared\Domain\Helpers\ArrayPresenter;
use AqHub\Shared\Infrastructure\Http\OpenAPI\QueryParameters\PageParameter;
use AqHub\Shared\Infrastructure\Http\OpenAPI\QueryParameters\PageSizeParameter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;

class CapeController implements RestController
{
    public function __construct(private readonly FindAll $findAll) {}

    #[OA\Get(
        path: '/capes/list',
        summary: 'List capes',
        tags: ['Capes'],
        parameters: [new PageParameter(), new PageSizeParameter(), new NameParameter(), new RaritiesParameter(), new TagsParamenter(), new OA\Parameter(parameter: 'page_size', name: 'can_access_bank', in: 'query', description: 'The cape can access bank (Y or N).', example: 'Y')],
        responses: [new ListCapesResponse()]
    )]
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
