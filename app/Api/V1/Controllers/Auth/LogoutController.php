<?php

namespace App\Api\V1\Controllers\Auth;

use App\Api\V1\Controllers\BaseController;
use Auth;

class LogoutController extends BaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', []);
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard()->logout();

        return response()
            ->json(['message' => 'Successfully logged out']);
    }
}
