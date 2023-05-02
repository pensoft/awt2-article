<?php

namespace App\Api\V1\Controllers\Users;

use App\Api\V1\Controllers\BaseController;
use App\Models\Role;
use App\Transformers\RoleTransformer;
use Dingo\Api\Http\Request;

class GetRolesController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/users/roles",
     *      operationId="getUsersRoles",
     *      tags={"Users"},
     *      summary="Get all roles",
     *      description="Returns all roles",
     *      security={{"passport":{}}},
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
     *             @OA\Schema(
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/Role"),
     *              ),
     *            )
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
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function __invoke(Request $request){
        $roles = Role::all();

        return $this->response->collection($roles, new RoleTransformer);
    }
}
