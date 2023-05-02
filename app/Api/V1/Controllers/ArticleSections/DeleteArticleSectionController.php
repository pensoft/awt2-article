<?php

namespace App\Api\V1\Controllers\ArticleSections;

use App\Api\V1\Controllers\BaseController;
use App\Models\ArticleSections;
use Dingo\Api\Http\Request;

class DeleteArticleSectionController extends BaseController
{
    /**
     * @OA\Delete (
     *      path="/articles/sections/{id}",
     *      operationId="deleteArticleSections",
     *      tags={"ArticleSection"},
     *      summary="Delete the article section",
     *      description="Delete the article section",
     *      security={{"passport":{}}},
     *
     *     @OA\Parameter(
     *          name="id",
     *          description="Article Section id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
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
     * @return \Dingo\Api\Http\Response
     */
    public function __invoke(Request $request, $id)
    {
        ArticleSections::whereId($id)->delete();

        return $this->response->noContent();
    }
}
