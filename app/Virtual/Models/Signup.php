<?php

namespace App\Virtual\Models;


/**
 * @OA\Schema(
 *     title="Signup",
 *     description="Signup result object",
 *     @OA\Xml(
 *         name="Signup"
 *     )
 * )
 */
class Signup
{
    /**
     * @OA\Property(
     *      title="Status",
     *      description="The status of the operation",
     *      example="ok"
     * )
     *
     * @var string
     */
    private $status;

}
