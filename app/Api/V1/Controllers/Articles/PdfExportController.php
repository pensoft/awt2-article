<?php

namespace App\Api\V1\Controllers\Articles;

use App\Api\V1\Controllers\BaseController;
use App\DTO\Pdf\PdfExportDTO;
use App\Models\Articles;
use App\Services\PdfService;
use Dingo\Api\Http\Request;
use OpenApi\Annotations as OA;

class PdfExportController extends BaseController
{
    public function __construct(private PdfService $pdfService){

    }
    /**
     * @OA\Post(
     *      path="/articles/items/{id}/pdf/export",
     *      operationId="PdfExportArticle",
     *      tags={"Article"},
     *      summary="Export article in pdf forma",
     *      description="Pdf export",
     *      security={{"passport":{}}},
     *     @OA\Parameter(
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
     *          @OA\Schema()
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
     *            @OA\Schema(
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Schema(),
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
     * @return object
     */
    public function __invoke(Request $request, $id)
    {
        $user = auth()->user();

        try {
            $article = Articles::uuid($id);
            $articleData = $this->pdfService->getArticleData($id);
            $requestJson = json_decode($request->getContent());
            foreach((array)$requestJson as $key=>$val) {
                $articleData->{$key} = $val;
            }
            $articleData->action = 'export';
            $articleData->articleTitle = $article->name;
            $articleData->articleId = $article->uuid;

            return $this->pdfService->createTaskForPdfExport($articleData);

        } catch (\Exception $exception) {
            return $this->response->errorNotFound();
        }
    }
}
