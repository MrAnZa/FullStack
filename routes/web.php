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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/pruebas/animales','PruebaController@index');
Route::get('/test-orm','PruebaController@testOrm');

//RUTAS API
Route::get('/usuario/pruebas','UserController@pruebas');
Route::get('/categoria/pruebas','CategoryController@pruebas');
Route::get('/entrada/pruebas','PostController@pruebas');

//Route::post('/api/register','UserController@register');
//Route::post('/api/login','UserController@login');