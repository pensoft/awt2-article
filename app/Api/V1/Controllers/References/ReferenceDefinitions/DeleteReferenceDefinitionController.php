<?php

namespace App\Api\V1\Controllers\References\ReferenceDefinitions;

use App\Api\V1\Controllers\BaseController;
use App\Models\Layout;
use App\Models\ReferenceDefinition;
use Dingo\Api\Http\Request;

class DeleteReferenceDefinitionController extends BaseController
{
    /**
     * @OA\Delete (
     *      path="/references/definitions/{id}",
     *      operationId="deleteReferenceDefinition",
     *      tags={"ReferenceDefinition"},
     *      summary="Delete the Reference Definition",
     *      description="Delete the Reference Definition",
     *      security={{"passport":{}}},
     *
     *     @OA\Parameter(
     *          name="id",
     *          description="ReferenceDefinition id",
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
        $request->user()->authorizeRoles(['admin']);

        ReferenceDefinition::whereId($id)->delete();

        return $this->response->noContent();
    }
}
