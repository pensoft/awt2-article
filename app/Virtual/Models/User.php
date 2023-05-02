<?php

namespace App\Virtual\Models;

/**
 * @OA\Schema(
 *     title="User",
 *     description="Authenticated user model",
 *     @OA\Xml(
 *         name="User"
 *     )
 * )
 */
class User extends BaseModels
{

    /**
     * @OA\Property(
     *     title="Id",
     *     description="The ID of the authenticated user",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $id;


    /**
     * @OA\Property(
     *      title="Name",
     *      description="The user\s name",
     *      example="Иван Иванов"
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *      title="Email",
     *      description="The user's email",
     *      example="ivan.ivanov@arpha.com"
     * )
     *
     * @var string
     */
    private $email;

    /**
     * @OA\Property(
     *     title="Role",
     *     description="The user's role",
     *     @OA\Property(
     *      property="data",
     *     ),
     * ),
     * @var \App\Virtual\Models\Role
     */
    private $role;
}
