<?php

namespace App\Api\V1\Controllers\Layouts;

use App\Api\V1\Controllers\BaseController;
use App\Models\Layout;
use App\Transformers\LayoutTransformer;
use Dingo\Api\Http\Request;

class GetLayoutController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/layouts/{id}",
     *      operationId="getLayoutById",
     *      tags={"Layout"},
     *      summary="Get specific layout",
     *      description="Returns layout data",
     *      security={{"passport":{}}},
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
     *          response=201,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
     *            @OA\Schema(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Layout",
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
            $user = Layout::findOrFail($id);
            return $this->response->item($user, new LayoutTransformer());
        } catch (\Exception $exception) {
            return $this->response->errorNotFound();
        }
    }
}
