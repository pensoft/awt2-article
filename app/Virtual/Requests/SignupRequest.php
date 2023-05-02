<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Signup user request",
 *      description="Signup user parameters",
 *      type="object",
 *      required={"name", "email","password"},
 *      @OA\Xml(
 *         name="SignupRequest"
 *      )
 * )
 */
class SignupRequest
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
}
