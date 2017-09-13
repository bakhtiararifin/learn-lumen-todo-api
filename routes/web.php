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

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->post('/register', 'AuthController@register');
$app->post('/login', 'AuthController@login');
$app->post('/change-password', 'AuthController@changePassword');

$app->post('/posts', 'PostController@index');
$app->post('/posts/detail', 'PostController@detail');
$app->post('/posts/add', 'PostController@add');
$app->post('/posts/update', 'PostController@update');
$app->post('/posts/delete', 'PostController@delete');