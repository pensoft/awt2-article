<?php

namespace App\Api\V1\Controllers\Auth;

use App\Api\V1\Controllers\BaseController;
use App\Transformers\UserTransformer;
use Auth;

class UserController extends BaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', []);
    }

    /**
     * @OA\Get(
     *      path="/auth/me",
     *      operationId="getAuthenticatedUser",
     *      tags={"Auth"},
     *      summary="Get authenticated user infromation",
     *      description="Returns current user data",
     *      security={{"passport":{}}},
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
     *            @OA\Schema(ref="#/components/schemas/User")
     *          )
     *       ),
     *
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
     *
     * Get the authenticated User
     *
     * @return \Dingo\Api\Http\Response
     */
    public function me()
    {
        return $this->response->item(Auth::guard()->user(), new UserTransformer);
    }
}
