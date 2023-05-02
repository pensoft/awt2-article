<?php

namespace App\Api\V1\Controllers\References\ReferenceDefinitions;

use App\Api\V1\Controllers\BaseController;
use App\Models\ReferenceDefinition;
use App\Transformers\ReferenceDefinitionTransformer;
use Dingo\Api\Http\Request;

class GetReferenceDefinitionController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/references/definitions/{id}",
     *      operationId="getReferenceDefinitionById",
     *      tags={"ReferenceDefinition"},
     *      summary="Get Reference Definition",
     *      description="Returns Reference Definition data",
     *      security={{"passport":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="Reference Definition id",
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
     *                  ref="#/components/schemas/ReferenceDefinition",
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
            $item = ReferenceDefinition::findOrFail($id);
            return $this->response->item($item, new ReferenceDefinitionTransformer());
        } catch (\Exception $exception) {
            return $this->response->errorNotFound();
        }
    }
}
