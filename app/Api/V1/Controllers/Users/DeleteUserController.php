<?php

namespace App\Api\V1\Controllers\Users;


use App\Api\V1\Controllers\BaseController;
use App\Models\User;
use Dingo\Api\Http\Request;

class DeleteUserController extends BaseController
{
    /**
     * @OA\Delete (
     *      path="/users/{id}",
     *      operationId="deleteUser",
     *      tags={"Users"},
     *      summary="Delete the user",
     *      description="Delete the user",
     *      security={{"passport":{}}},
     *
     *     @OA\Parameter(
     *          name="id",
     *          description="User id",
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
     * @param int $id
     * @return \Dingo\Api\Http\Response
     */
    public function __invoke(Request $request, $id)
    {
        $request->user()->authorizeRoles(['admin']);

        User::whereId($id)->delete();

        return $this->response->noContent();
    }
}
