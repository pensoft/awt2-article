<?php

namespace App\Virtual\Models;


/**
 * @OA\Schema(
 *     title="Create User",
 *     description="Create User result object",
 *     @OA\Xml(
 *         name="CreateUser"
 *     )
 * )
 */
class CreateUser
{
    /**
     * @OA\Property(
     *     title="Email",
     *     description="User's email"
     * )
     *
     * @var string
     */
    private $email;

    /**
     * @OA\Property(
     *     title="Password",
     *     description="User's password"
     * )
     *
     * @var string
     */
    private $password;

    /**
     * @OA\Property(
     *     title="Name",
     *     description="User's name"
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *     title="Role Id",
     *     description="User's role"
     * )
     *
     * @var integer
     */
    private $role_id;

}
