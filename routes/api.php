<?php

use Illuminate\Http\Request;
use App\Http\Middleware\ApiAuthMiddleware;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login','UserController@login');
Route::post('/register','UserController@register');
Route::put('/user/update','UserController@update');
Route::post('/user/upload','UserController@upload')->middleware(ApiAuthMiddleware::class);
Route::get('/user/avatar/{filename}', 'UserController@getImage');
Route::get('/user/detail/{id}','UserController@detail');

Route::resource('/category','CategoryController');
Route::resource('/post', 'PostController');
Route::post('/post/upload','PostController@upload');