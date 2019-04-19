<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/debug', 'ExampleController@debug');
$router->post('/login', 'AuthController@login');
$router->post('/register', 'AuthController@register');

$router->group(['middleware' => ['jwt_auth']], function () use ($router) {

    $router->get('/me', 'AuthController@me');
    $router->post('/logout', 'AuthController@logout');

    $router->group(['prefix' => 'file'], function () use ($router) {
        $router->post('/upload-temp', 'FileController@uploadTemp');
    });

    $router->group(['prefix' => 'course'], function () use ($router) {

        $router->get('/list', 'CourseController@list');
        $router->post('/submit', 'CourseController@submit');
    });
});