<?php

namespace App\Api\V1\Controllers\Articles;

use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Requests\Articles\UpdateArticleRequest;
use App\Api\V1\Requests\ArticleSections\UpdateArticleSectionRequest;
use App\Models\Articles;
use App\Transformers\ArticleTransformer;
use OpenApi\Annotations as OA;

class UpdateArticleController extends BaseController
{
    /**
     * @OA\Put(
     *      path="/articles/items/{id}",
     *      operationId="updateArticle",
     *      tags={"Article"},
     *      summary="Update the article",
     *      description="Update the article",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Article id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateArticleRequest")
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
     *            @OA\Schema(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Article",
     *              ),
     *            )
     *          )
     *       ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *     @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="Internal error"
     *      )
     * )
     *
     * @param UpdateArticleSectionRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response|void
     */
    public function __invoke(UpdateArticleRequest $request, $id)
    {
        $request->user()->authorizeRoles(['admin', 'author', 'editor']);

        try {
            $article = Articles::findOrFail($id);
            $article->fill([
                'name'=>$request->get('name')
            ])->save();

            $article->fresh();

            return $this->response->item($article, new ArticleTransformer);
        } catch (\Exception $exception) {
            return $this->response->errorNotFound();
        }
    }
}
