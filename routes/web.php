<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

/** @var \Laravel\Lumen\Routing\Router $router */

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

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });
$router->group(['prefix' => 'api'], function () use ($router) {
$router->post('/register', 'AuthController@register');
$router->post('/login', 'AuthController@login');
});


$router->group(['prefix' => 'api','middleware' => 'auth:api'], function () use ($router) {
    $router->get('/posts', 'PostController@index');
    $router->post('/posts', 'PostController@store');
    $router->get('/posts/{id}', 'PostController@show');
    $router->put('/posts/{id}', 'PostController@update');
    $router->delete('/posts/{id}', 'PostController@destroy');
    $router->get('/posts/{id}/tags', 'PostController@tags');



    $router->get('/tags', 'TagController@index');
    $router->post('/tags', 'TagController@store');
    $router->get('/tags/{id}', 'TagController@show');
    $router->put('/tags/{id}', 'TagController@update');
    $router->delete('/tags/{id}', 'TagController@destroy');
    $router->get('/tags/{id}/post', 'TagController@post');



  
    $router->post('/logout', 'AuthController@logout');





});


