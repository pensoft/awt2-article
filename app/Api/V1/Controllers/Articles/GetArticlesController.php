<?php

namespace App\Api\V1\Controllers\Articles;

use App\Api\V1\Controllers\BaseController;
use App\Models\Articles;
use App\QueryBuilder\Filters\FiltersExactOrNotExact;
use App\Transformers\ArticleShortTransformer;
use App\Transformers\ArticleTransformer;
use Dingo\Api\Http\Request;
use OpenApi\Annotations as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\ArticleCollaborators;

class GetArticlesController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/articles/items",
     *      operationId="getArticles",
     *      tags={"Article"},
     *      summary="Get all articles",
     *      description="Returns all articles",
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
     *                  @OA\Items(ref="#/components/schemas/Article"),
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
        $filtered = $request->get('filtered', true) === 'false' || in_array(strtolower($request->user()->role), ['superadmin', 'admin']);

        $articles = QueryBuilder::for(!$filtered ? $this->buildPerUserModel() : Articles::class)
            ->defaultSort('id')
            ->allowedSorts('id', 'name', 'user_id')
            ->allowedFilters('name', 'layout_id',
                AllowedFilter::custom('id', new FiltersExactOrNotExact),
            )->paginate($pageSize);

        $filters = request()->query();

        return $this->response->paginator($articles, new ArticleShortTransformer)->setMeta($filters);
    }

    private function buildPerUserModel(){

        $user = auth()->user();

        $collaboratorArticle = ArticleCollaborators::where('user_id', $user->id)->pluck('article_id')->toArray();

        return Articles::where(function($query) use($user, $collaboratorArticle){
            $query->where('user_id', $user->id)->orWhereIn('uuid', $collaboratorArticle);
        });

    }
}
