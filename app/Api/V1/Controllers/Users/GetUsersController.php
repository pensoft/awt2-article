<?php

namespace App\Api\V1\Controllers\Users;

use App\Api\V1\Controllers\BaseController;
use App\Models\User;
use App\Transformers\UserTransformer;
use Dingo\Api\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class GetUsersController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/users",
     *      operationId="getUsers",
     *      tags={"Users"},
     *      summary="Get all users",
     *      description="Returns all users",
     *      security={{"passport":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *         in="query",
     *         description="page",
     *         example=1,
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         name="pageSize",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *         in="query",
     *         description="Page size",
     *         example=10,
     *         required=false,
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
     *            @OA\Schema(
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/User"),
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
        $pageSize = $request->get('pageSize', 10);
        $users = QueryBuilder::for(User::class)
            ->defaultSort('id')
            ->allowedSorts('id', 'name', 'email')
            ->allowedFilters('name')->paginate($pageSize);

        $filters = request()->query();
        return $this->response->paginator($users, new UserTransformer)->setMeta($filters);
    }
}
