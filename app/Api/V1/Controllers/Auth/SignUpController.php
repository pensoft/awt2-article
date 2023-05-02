<?php

namespace App\Api\V1\Controllers\Auth;

use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Requests\SignUpRequest;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTAuth;

class SignUpController extends BaseController
{
    /**
     *  @OA\Post(
     *      path="/auth/signup",
     *      operationId="signup",
     *      tags={"Auth"},
     *      summary="Sign up a new user",
     *      description="Create a new user",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/SignupRequest")
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
     *            @OA\Schema(ref="#/components/schemas/Signup")
     *          )
     *       ),
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
     * @param SignUpRequest $request
     * @param JWTAuth $JWTAuth
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp(SignUpRequest $request, JWTAuth $JWTAuth)
    {
        $user = new User($request->all());
        $user->role_id = 3;

        if(!$user->save()) {
            throw new HttpException(500);
        }

        if(!config('boilerplate.sign_up.release_token')) {
            return response()->json([
                'status' => 'ok'
            ], 201);
        }

        $token = $JWTAuth->fromUser($user);
        return response()->json([
            'status' => 'ok',
            'token' => $token
        ], 201);
    }
}
