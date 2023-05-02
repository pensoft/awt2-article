<?php

use App\Api\V1\Controllers\Test;
use App\Api\V1\Controllers\ArticleTemplates\{
    CreateArticleTemplateController,
    DeleteArticleTemplateController,
    GetArticleTemplateController,
    GetArticleTemplatesController,
    UpdateArticleTemplateController
};
use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    //$api->group(['middleware' => 'jwt.auth'], function (Router $api) {
        $api->group(['prefix' => 'articles'], function (Router $api) {
            $api->group(['prefix' => 'templates'], function (Router $api) {
                $api->get('/test', Test::class);
                $api->get('/{id}', GetArticleTemplateController::class);
                $api->get('/', GetArticleTemplatesController::class);
                $api->post('/', CreateArticleTemplateController::class);
                $api->put('/{id}', UpdateArticleTemplateController::class);
                $api->delete('/{id}', DeleteArticleTemplateController::class);


            });
        });

    //});
});
