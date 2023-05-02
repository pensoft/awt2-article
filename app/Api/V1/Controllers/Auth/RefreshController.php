<?php

namespace App\Api\V1\Controllers\Auth;

use App\Api\V1\Controllers\BaseController;
use Auth;

class RefreshController extends BaseController
{
    /**
     *  @OA\Post(
     *      path="/refresh",
     *      operationId="refresh",
     *      tags={"Auth"},
     *      summary="Refresh a token",
     *      description="Login the user using his credentials",
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
     *            @OA\Schema(
     *              schema="message",
     *              type="string",
     *              description="The unique identifier of a product in our catalog",
     *              example="Token is refreshed"
     *            )
     *          )
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="Internal error"
     *      )
     * )
     *
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $token = Auth::guard()->refresh();

        return response()->json([
            'status' => 'ok',
            'token' => $token,
            'expires_in' => Auth::guard()->factory()->getTTL() * 60
        ]);
    }
}
