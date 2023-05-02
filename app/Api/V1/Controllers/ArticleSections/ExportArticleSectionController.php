<?php

namespace App\Api\V1\Controllers\ArticleSections;

use App\Api\V1\Controllers\BaseController;
use App\Models\ArticleSections;
use App\Transformers\ArticleSectionExportTransformer;
use App\Transformers\ArticleSectionTransformer;
use Dingo\Api\Http\Request;

class ExportArticleSectionController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/articles/sections/{id}/export",
     *      operationId="exportArticleSectionById",
     *      tags={"ArticleSection"},
     *      summary="Export specific article section",
     *      description="Returns article section export data",
     *      security={{"passport":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="Article section id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
     *            @OA\Schema(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/ArticleSection",
     *              ),
     *            )
     *          )
     *       ),
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
     * @param Request $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function __invoke(Request $request, $id)
    {
        try {
            $user = ArticleSections::findOrFail($id);
            return $this->response->item($user, new ArticleSectionExportTransformer());
        } catch (\Exception $exception) {
            return $this->response->errorNotFound();
        }
    }
}
