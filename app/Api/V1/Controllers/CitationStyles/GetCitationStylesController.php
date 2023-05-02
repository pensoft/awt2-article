<?php
namespace App\Api\V1\Controllers\CitationStyles;

use App\Api\V1\Controllers\BaseController;
use App\Models\CitationStyle;
use App\QueryBuilder\Filters\FiltersExactOrNotExact;
use App\Transformers\CitationStyleShortTransformer;
use Dingo\Api\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class GetCitationStylesController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/citation-styles",
     *      operationId="getCitationStyles",
     *      tags={"CitationStyle"},
     *      summary="Get all citation styles",
     *      description="Returns all citation styles",
     *      security={{"passport":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *         in="query",
     *         description="page",
     *         example=1,
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         name="pageSize",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *         in="query",
     *         description="Page size",
     *         example=10,
     *         required=false,
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
     *            @OA\Schema(
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/CitationStyle"),
     *              ),
     *            )
     *          )
     *       ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function __invoke(Request $request)
    {
        $pageSize = $request->get('pageSize', 10);

        $citationStyle = QueryBuilder::for(CitationStyle::class)
            ->defaultSort('title')
            ->allowedSorts('id', 'title')
            ->allowedFilters('title', 'name',
                AllowedFilter::custom('id', new FiltersExactOrNotExact),
            )->paginate($pageSize);

        $filters = request()->query();
        return $this->response->paginator($citationStyle, new CitationStyleShortTransformer)->setMeta($filters);
    }
}
