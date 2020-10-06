<?php

use Illuminate\Http\Request;
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

// 'prefix' => 'auth'

Route::group(['middleware' => 'api', 'prefix' => 'V1'], function ($router) {

    //User
    Route::get('notLogin', 'AuthController@notLogin')->name('notLogin');
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('user-profile', 'AuthController@userProfile');

    //Article
    Route::post('/article','API\ArticleController@store');
    Route::get('/article','API\ArticleController@show');
    Route::get('/article/{id}', 'API\PegawaiController@find');
    Route::post('/article/{id}','API\ArticleController@update');
    Route::delete('/article/{id}', 'API\ArticleController@delete');
    Route::post('/article/img-upload', 'API\ArticleController@storeImg'); 

    //Author
    Route::post('/author','API\AuthorController@store');
    Route::get('/author','API\AuthorController@show');
    Route::get('/author/{id}', 'API\AuthorController@find');
    Route::post('/author/{id}','API\AuthorController@update');
    Route::delete('/author/{id}', 'API\AuthorController@delete');

    //Category
    Route::post('/category','API\CategoryController@store');
    Route::get('/category','API\CategoryController@show');
    Route::get('/category/{id}', 'API\CategoryController@find');
    Route::post('/category/{id}','API\CategoryController@update');
    Route::delete('/category/{id}', 'API\CategoryController@delete');
});


