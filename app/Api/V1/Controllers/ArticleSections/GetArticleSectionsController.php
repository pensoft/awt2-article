<?php

namespace App\Api\V1\Controllers\ArticleSections;

use App\Api\V1\Controllers\BaseController;
use App\Models\ArticleSections;
use App\QueryBuilder\Filters\FiltersExactOrNotExact;
use App\Transformers\ArticleSectionShortTransformer;
use App\Transformers\ArticleSectionTransformer;
use Dingo\Api\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class GetArticleSectionsController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/articles/sections",
     *      operationId="getArticleSections",
     *      tags={"ArticleSection"},
     *      summary="Get all article sections",
     *      description="Returns all article sections",
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
     *                  @OA\Items(ref="#/components/schemas/ArticleSection"),
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
    public function __invoke(Request $request){
        $pageSize = $request->get('pageSize', 10);
        $sections = QueryBuilder::for(ArticleSections::class)
            ->defaultSort('id')
            ->allowedSorts('id', 'name')
            ->allowedFilters('name', 'type',
                AllowedFilter::custom('id', new FiltersExactOrNotExact),
            )->paginate($pageSize);

        $filters = request()->query();
        return $this->response->paginator($sections, new ArticleSectionShortTransformer)->setMeta($filters);
    }
}
