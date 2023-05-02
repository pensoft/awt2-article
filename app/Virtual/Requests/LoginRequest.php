<?php
namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Login user request",
 *      description="Login user parameters",
 *      type="object",
 *      required={"email","password"},
 *      @OA\Xml(
 *         name="LoginRequest"
 *      )
 * )
 */
class LoginRequest {
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
}
