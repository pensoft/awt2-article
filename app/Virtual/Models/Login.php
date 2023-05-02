<?php

namespace App\Virtual\Models;


/**
 * @OA\Schema(
 *     title="Login",
 *     description="Login result object",
 *     @OA\Xml(
 *         name="Login"
 *     )
 * )
 */
class Login
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

    /**
     * @OA\Property(
     *      title="Access Token",
     *      description="The access token",
     *      example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vYXJ0aWNsZS1hcGkuY29tL2FwaS9hdXRoL2xvZ2luIiwiaWF0IjoxNjM3Nzc2MzU0LCJleHAiOjE2Mzc3Nzk5NTQsIm5iZiI6MTYzNzc3NjM1NCwianRpIjoidGJEcTJmaHRkQWxZNFI4cSIsInN1YiI6MSwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.kWsb0pGpTCT-8S4hPuYUqTDvqTlQp3lI-CXK1zAbktY"
     * )
     *
     * @var string
     */
    private $accessToken;

    /**
     * @OA\Property(
     *      title="Expire in",
     *      description="The life time of the token in secconds",
     *      example="3600"
     * )
     *
     * @var int
     */
    private $expiresIn;
}
