<?php

use App\Api\V1\Controllers\Articles\PdfExportController;
use App\Api\V1\Controllers\Test;
use Dingo\Api\Routing\Router;
use Illuminate\Support\Facades\Auth;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    //$api->group(['middleware' => 'jwt.auth'], function (Router $api) {
        $api->group(['prefix' => 'articles'], function (Router $api) {
            $api->group(['prefix' => 'items'], function (Router $api) {
                Auth::guard()->user();

                $api->post('/{id}/pdf/export', PdfExportController::class);

                $api->get('/{id}', 'App\\Api\\V1\\Controllers\\Articles\\GetArticleController');
                $api->get('/uuid/{id}', 'App\\Api\\V1\\Controllers\\Articles\\GetArticleByUuidController');
                $api->get('/', 'App\\Api\\V1\\Controllers\\Articles\\GetArticlesController');
                $api->post('/', 'App\\Api\\V1\\Controllers\\Articles\\CreateArticleController');
                $api->put('/{id}', 'App\\Api\\V1\\Controllers\\Articles\\UpdateArticleController');
                $api->patch('/{id}', App\Api\V1\Controllers\Articles\PatchArticleController::class);
                $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\Articles\\DeleteArticleController');
            });
        });
    //});
});
