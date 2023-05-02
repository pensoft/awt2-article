<?php

namespace App\Api\V1\Controllers\Layouts;

use App\Api\V1\Controllers\BaseController;
use App\Models\Layout;
use Dingo\Api\Http\Request;

class DeleteLayoutController extends BaseController
{
    /**
     * @OA\Delete (
     *      path="/layouts/{id}",
     *      operationId="deleteLayout",
     *      tags={"Layout"},
     *      summary="Delete the layout",
     *      description="Delete the layout",
     *      security={{"passport":{}}},
     *
     *     @OA\Parameter(
     *          name="id",
     *          description="Layout id",
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
        Layout::whereId($id)->delete();

        return $this->response->noContent();
    }
}
