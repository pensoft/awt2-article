<?php

use App\Api\V1\Controllers\References\ReferenceItems\{
    CreateReferenceItemController,
    DeleteReferenceItemController,
    GetReferenceItemController,
    GetReferenceItemsController,
    UpdateReferenceItemController
};
use App\Api\V1\Controllers\References\ReferenceDefinitions\{
    CreateReferenceDefinitionController,
    GetReferenceDefinitionController,
    DeleteReferenceDefinitionController,
    UpdateReferenceDefinitionController,
    GetReferenceDefinitionsController
};
use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    //$api->group(['middleware' => ['jwt.auth']], function (Router $api) {
        $api->group(['prefix' => 'references'], function (Router $api) {
            $api->group(['prefix' => 'definitions'], function (Router $api) {
                $api->get('/{id}', GetReferenceDefinitionController::class);
                $api->get('/', GetReferenceDefinitionsController::class);
                $api->post('/', CreateReferenceDefinitionController::class);
                $api->put('/{id}', UpdateReferenceDefinitionController::class);
                $api->delete('/{id}', DeleteReferenceDefinitionController::class);
            });
            $api->group(['prefix' => 'items'], function (Router $api) {
                $api->get('/{id}', GetReferenceItemController::class);
                $api->get('/', GetReferenceItemsController::class);
                $api->post('/', CreateReferenceItemController::class);
                $api->put('/{id}', UpdateReferenceItemController::class);
                $api->delete('/{id}', DeleteReferenceItemController::class);
            });
        });
    //});
});
