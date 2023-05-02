<?php

namespace App\Http\Middleware;

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Log;

class TransformArticleSettings {

    public function handle($request, \Closure  $next)
    {
        $response = $next($request);

        //ddh($response);

        return $response;
    }

    public function terminate($request, $response)
    {
        //Log::info('app.requests', ['request' => $request->all(), 'response' => $response]);
    }

}
