<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */


Route::post('/login', 'App\Http\Controllers\User@login');
Route::post('/login/2factor-auth', 'App\Http\Controllers\User@two_factor_auth');


Route::group(['middleware' => ['auth:sanctum', 'auth:api']],function () {
    Route::get('users/me', 'App\Http\Controllers\User@showmyinfo');
    Route::put('users/me', 'App\Http\Controllers\User@updateMyInfo');
});
Route::group(['middleware' => ['auth:sanctum', 'auth:api']], function () {
    Route::get('users', 'App\Http\Controllers\User@index')->middleware('can:isAdmin');
    Route::get('users/{id}', 'App\Http\Controllers\User@show')->middleware('can:isAdmin');
    Route::post('users/', 'App\Http\Controllers\User@store')->middleware('can:isAdmin');
    Route::put('users/{id}', 'App\Http\Controllers\User@update')->middleware('can:isAdmin');
    Route::delete('users/{id}', 'App\Http\Controllers\User@destroy')->middleware('can:isAdmin');
    Route::get('roles', 'App\Http\Controllers\RolesController@index');
});


Route::group(['middleware' => ['auth:sanctum', 'auth:api']],function () {
    
    //Route::resource('posts', 'App\Http\Controllers\PostController');
    Route::get('posts', 'App\Http\Controllers\PostController@index')->can('viewAny', '\App\Models\Post');
    Route::get('posts/{slug}', 'App\Http\Controllers\PostController@show')->can('viewAny', '\App\Models\Post');
    //Route::get('posts/{slug}', 'App\Http\Controllers\PostController@show')->can('view', '\App\Models\User', '\App\Models\Post');
    Route::post('posts', 'App\Http\Controllers\PostController@store')->can('create', '\App\Models\Post');
    //Route::get('posts', 'App\Http\Controllers\PostController@index')->can('view');
    Route::put('posts/{id}', 'App\Http\Controllers\PostController@update')->can('update', '\App\Models\Post');
    Route::delete('posts/{id}', 'App\Http\Controllers\PostController@destroy')->can('delete', '\App\Models\Post');
});


//spartie