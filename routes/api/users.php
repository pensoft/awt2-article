<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    //$api->group(['middleware' => 'jwt.auth'], function(Router $api) {
        $api->group(['prefix' => 'users'], function(Router $api) {
            $api->get('/roles', 'App\\Api\\V1\\Controllers\\Users\\GetRolesController');

            $api->get('/{id}', 'App\\Api\\V1\\Controllers\\Users\\GetUserController');
            $api->get('/', 'App\\Api\\V1\\Controllers\\Users\\GetUsersController');
            $api->post('/', 'App\\Api\\V1\\Controllers\\Users\\CreateUserController');
            $api->put('/{id}', 'App\\Api\\V1\\Controllers\\Users\\UpdateUserController');
            $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\Users\\DeleteUserController');
        });

    //});
});
