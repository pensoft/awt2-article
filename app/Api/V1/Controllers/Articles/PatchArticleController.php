<?php

namespace App\Api\V1\Controllers\Articles;

use App\Api\V1\Controllers\BaseController;
use App\Models\Articles;
use App\Transformers\ArticleTransformer;
use Dingo\Api\Http\Request;
use OpenApi\Annotations as OA;

class PatchArticleController extends BaseController
{
    /**
     * @OA\Patch(
     *      path="/articles/items/{id}",
     *      operationId="patchArticle",
     *      tags={"Article"},
     *      summary="Patch the article",
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
     *          required=false,
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
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function __invoke(Request $request, $id)
    {
        try {
            $article = Articles::findOrFail($id);
            $article->fill($request->only(['name']));
            $article->save();

            $article->touch();
            $article->fresh();

            return $this->response->item($article, new ArticleTransformer);
        } catch (\Exception $exception) {
            return $this->response->errorNotFound();
        }
    }
}
