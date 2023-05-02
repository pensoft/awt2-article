<?php

use App\Api\V1\Controllers\Layouts\GetLayoutWithoutTransformController;
use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    //$api->group(['middleware' => ['jwt.auth']], function (Router $api) {
        $api->group(['prefix' => 'layouts'], function (Router $api) {
                $api->get('/simple', 'App\\Api\\V1\\Controllers\\Layouts\\GetSimpleLayoutsController');
                $api->get('/{id}', 'App\\Api\\V1\\Controllers\\Layouts\\GetLayoutController');
                $api->get('/withoutTransform/{id}', GetLayoutWithoutTransformController::class);
                $api->get('/', 'App\\Api\\V1\\Controllers\\Layouts\\GetLayoutsController');
                $api->post('/', 'App\\Api\\V1\\Controllers\\Layouts\\CreateLayoutController');
                $api->put('/{id}', 'App\\Api\\V1\\Controllers\\Layouts\\UpdateLayoutController');
                $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\Layouts\\DeleteLayoutController');
            });
    //});
});
