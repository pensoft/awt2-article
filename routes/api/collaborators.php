<?php

use App\Api\V1\Controllers\Collaborators\InviteCollaboratorController;
use Dingo\Api\Routing\Router;
use Illuminate\Support\Facades\Auth;
use App\Api\V1\Controllers\Collaborators\CommentCollaboratorController;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->group(['prefix' => 'collaborators'], function (Router $api) {
        Auth::guard()->user();
        $api->post('/comment', CommentCollaboratorController::class);
        $api->post('/invite', [InviteCollaboratorController::class, 'store']);
        $api->patch('/invite', [InviteCollaboratorController::class, 'update']);
        $api->delete('/invite', [InviteCollaboratorController::class, 'delete']);
    });
});
