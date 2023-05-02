<?php

use App\Api\V1\Controllers\Layouts\GetLayoutWithoutTransformController;
use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    //$api->group(['middleware' => ['jwt.auth']], function (Router $api) {
        $api->group(['prefix' => 'citation-styles'], function (Router $api) {
                $api->get('/{id}', 'App\\Api\\V1\\Controllers\\CitationStyles\\GetCitationStyleController');
                $api->get('/', 'App\\Api\\V1\\Controllers\\CitationStyles\\GetCitationStylesController');
            });
    //});
});
