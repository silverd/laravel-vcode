<?php

use Illuminate\Routing\Router;

Route::group([
    'prefix'        => config('vcode.route.prefix'),
    'middleware'    => config('vcode.route.middleware'),
    'namespace'     => 'Cann\Vcode\Controllers',
], function (Router $router) {

    $router->post('/vcode', 'VcodeController@send');

});
