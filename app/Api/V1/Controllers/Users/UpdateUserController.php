<?php

namespace App\Api\V1\Controllers\Users;


use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Requests\Users\UpdateUserRequest;
use App\Models\User;
use App\Transformers\UserTransformer;

class UpdateUserController extends BaseController
{
    /**
     * @OA\Put(
     *      path="/users/{id}",
     *      operationId="updateUser",
     *      tags={"Users"},
     *      summary="Update the user",
     *      description="Update the user",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateUserRequest")
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
     *            @OA\Schema(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/CreateUser",
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
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return \Dingo\Api\Http\Response
     */
    public function __invoke(UpdateUserRequest $request, $id)
    {
        $request->user()->authorizeRoles(['admin']);

        $user = User::whereId($id)->first();
        $user->role_id = $request->get('role_id');
        $user->name = $request->get('name');
        $user->email = $request->get('email');

        if (!empty($request->get('password'))) {
            $user->password = bcrypt($request->get('password'));
        }
        $user->save();

        return $this->response->item($user, new UserTransformer);
    }
}
