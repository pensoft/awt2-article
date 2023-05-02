<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    //$api->group(['middleware' => 'jwt.auth'], function (Router $api) {
        $api->group(['prefix' => 'articles', 'as' => 'article'], function (Router $api) {
            $api->group(['prefix' => 'sections'], function (Router $api) {


                $api->get('/{id}/export', 'App\\Api\\V1\\Controllers\\ArticleSections\\ExportArticleSectionController')->name('readAccount');
                $api->get('/{id}', 'App\\Api\\V1\\Controllers\\ArticleSections\\GetArticleSectionController');
                $api->get('/', 'App\\Api\\V1\\Controllers\\ArticleSections\\GetArticleSectionsController');
                $api->post('/', 'App\\Api\\V1\\Controllers\\ArticleSections\\CreateArticleSectionController');
                $api->put('/{id}', 'App\\Api\\V1\\Controllers\\ArticleSections\\UpdateArticleSectionController');
                $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\ArticleSections\\DeleteArticleSectionController');

                $api->post('/import', 'App\\Api\\V1\\Controllers\\ArticleSections\\ImportArticleSectionsController');
            });
        });

    //});
});
