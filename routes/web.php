<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Enums\ArticleSectionTypes;
use App\Http\Controllers\Test;
use App\Models\ArticleSections;
use App\Models\ArticleTemplates;

Route::get('reset_password/{token}', ['as' => 'password.reset', function($token)
{
    // implement your reset password route here!
}]);

Route::get('apiRoutes', function() {
    \Artisan::call('api:routes');
    return "<pre>" . \Artisan::output() . "</pre>";
});

Route::get('appRoutes', function() {
    \Artisan::call('route:list');
    return "<pre>" . \Artisan::output() . "</pre>";
});

foreach (File::allFiles(__DIR__ . '/web') as $partial) {
    require $partial->getPathname();
}
