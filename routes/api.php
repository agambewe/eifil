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
    Route::post('update', 'AuthController@update');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('user-profile', 'AuthController@userProfile');

    //Article
    Route::post('/article','API\ArticleController@store');
    Route::get('/article','API\ArticleController@show');
    Route::get('/article/{id}', 'API\ArticleController@find');
    Route::get('/article-image/{id}', 'API\ArticleController@findImage');
    Route::get('/article-by-category/{id}', 'API\ArticleController@findByCategory');
    Route::get('/article-by-author/{id}', 'API\ArticleController@findByAuthor');
    Route::get('/article-by-hashtag/{id}', 'API\ArticleController@findByHashtag');
    Route::get('/article-relate-top/{id}', 'API\ArticleController@topByHashtag');
    Route::post('/article/{id}','API\ArticleController@update');
    Route::delete('/article/{id}', 'API\ArticleController@delete');
    Route::get('/article-trash','API\ArticleController@trash');
    Route::post('/article-trash/{id}','API\ArticleController@trashFind');
    Route::post('/article-trash-restore','API\ArticleController@trashRestore');
    Route::delete('/article-trash-empty','API\ArticleController@trashEmpty');
    Route::delete('/article-trash-delete/{id}','API\ArticleController@trashDelete');
    Route::post('/article-multiple-delete', 'API\ArticleController@multipleDelete');
    Route::post('/article-multiple-delete-trash', 'API\ArticleController@multipleDeleteTrash');
    Route::delete('/article-all-delete', 'API\ArticleController@deleteAll');
    Route::delete('/article-all-delete-confirm', 'API\ArticleController@deleteAllConfirm');
    Route::post('/img-upload', 'API\ArticleController@storeImg'); 

    //Author
    Route::post('/author','API\AuthorController@store');
    Route::get('/author','API\AuthorController@show');
    Route::get('/author/{id}', 'API\AuthorController@find');
    Route::get('/author-trash','API\AuthorController@trash');
    Route::post('/author-trash/{id}','API\AuthorController@trashFind');
    Route::post('/author-trash-restore','API\AuthorController@trashRestore');
    Route::delete('/author-trash-empty','API\AuthorController@trashEmpty');
    Route::post('/author/{id}','API\AuthorController@update');
    Route::delete('/author/{id}', 'API\AuthorController@delete');
    Route::post('/author-multiple-delete', 'API\AuthorController@multipleDelete');

    //Category
    Route::post('/category','API\CategoryController@store');
    Route::get('/category','API\CategoryController@show');
    Route::get('/category/{id}', 'API\CategoryController@find');
    Route::get('/category-trash','API\CategoryController@trash');
    Route::post('/category-trash/{id}','API\CategoryController@trashFind');
    Route::post('/category-trash-restore','API\CategoryController@trashRestore');
    Route::delete('/category-trash-empty','API\CategoryController@trashEmpty');
    Route::delete('/category-trash-delete/{id}','API\CategoryController@trashDelete');
    Route::post('/category/{id}','API\CategoryController@update');
    Route::delete('/category/{id}', 'API\CategoryController@delete');
    Route::post('/category-multiple-delete', 'API\CategoryController@multipleDelete');
    Route::post('/category-multiple-delete-trash', 'API\ArticleController@multipleDeleteTrash');
});


