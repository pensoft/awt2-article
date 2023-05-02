<?php

namespace App\Api\V1\Controllers\Users;


use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Requests\Users\CreateUserRequest;
use App\Models\User;
use App\Transformers\UserTransformer;

class CreateUserController extends BaseController
{
    /**
     *  @OA\Post(
     *      path="/users",
     *      operationId="createUser",
     *      tags={"Users"},
     *      summary="Create a new user",
     *      description="Create a new user",
     *      security={{"passport":{}}},
     *
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CreateUserRequest")
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
     * @param CreateUserRequest $request
     * @param User $user
     * @return \Dingo\Api\Http\Response
     */
    public function __invoke(CreateUserRequest $request, User $user){
        $request->user()->authorizeRoles(['admin']);

        $user = User::create([
            'role_id'    => $request->get('role_id'),
            'name' => $request->get('name'),
            'email'      => $request->get('email'),
            'password'   => bcrypt($request->get('password')),
        ]);

        $user->fresh();

        return $this->response->item($user, new UserTransformer);
    }
}
