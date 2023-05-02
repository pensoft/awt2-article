<?php
namespace App\Api\V1\Controllers\CitationStyles;

use App\Api\V1\Controllers\BaseController;
use App\Models\CitationStyle;
use App\Transformers\CitationStyleTransformer;
use Dingo\Api\Http\Request;

class GetCitationStyleController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/citation-styles/{id}",
     *      operationId="getCitationStyleById",
     *      tags={"CitationStyle"},
     *      summary="Get specific citation style",
     *      description="Returns citation style data",
     *      security={{"passport":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="Citation Style id",
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
     *                  ref="#/components/schemas/CitationStyle",
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
            $style = CitationStyle::findOrFail($id);
            return $this->response->item($style, new CitationStyleTransformer());
        } catch (\Exception $exception) {
            return $this->response->errorNotFound();
        }
    }
}
