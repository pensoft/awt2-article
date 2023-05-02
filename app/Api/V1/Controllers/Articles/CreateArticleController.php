<?php
namespace App\Api\V1\Controllers\Articles;


use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Requests\Articles\CreateArticleRequest;
use App\Models\Articles;
use App\Models\Layout;
use App\Transformers\ArticleTransformer;
use OpenApi\Annotations as OA;


class CreateArticleController extends BaseController
{
    /**
     * @OA\Post(
     *      path="/articles/items",
     *      operationId="createArticle",
     *      tags={"Article"},
     *      summary="Create a new article",
     *      description="Create a new article",
     *      security={{"passport":{}}},
     *
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CreateArticleRequest")
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
     * @param CreateArticleRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function __invoke(CreateArticleRequest $request){
        $layout = Layout::findOrFail($request->get('layout_id'));
        $article = Articles::create([
            'name' =>$request->get('name'),
            'layout_id' =>$layout->id,
            'layout_version_id' => $layout->lastVersion->id,
            'user_id' => $request->user()->id,
            'user_name' => $request->user()->name,
            'user_email' => $request->user()->email,
        ]);

        $article->fresh('layout');

        return $this->response->item($article, new ArticleTransformer);
    }
}
