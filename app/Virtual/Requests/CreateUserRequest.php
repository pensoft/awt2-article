<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Create new user request",
 *      description="Create user parameters",
 *      type="object",
 *      required={"name", "email", "password", "role_id"},
 *      @OA\Xml(
 *         name="CreateUserRequest"
 *      )
 * )
 */
class CreateUserRequest
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
