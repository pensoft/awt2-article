<?php

namespace App\Virtual\Models;

/**
 * @OA\Schema(
 *     title="Role",
 *     description="Role model",
 *     @OA\Xml(
 *         name="Role"
 *     )
 * )
 */

class Role
{
    /**
     * @OA\Property(
     *     title="Id",
     *     description="The ID of the role",
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
     *      description="The role's name",
     *      example="Administrator"
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *      title="Role key",
     *      description="The role's key",
     *      example="admin"
     * )
     *
     * @var string
     */
    private $role;
}
