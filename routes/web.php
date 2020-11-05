<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/tiny', function () {
    return view('article-form');
});

//Summer Note
// Route::get('img','ArticleController@index');
// Route::post('img-upl','ArticleController@store');

//tinyMCE
Route::post('/save-article','API\ArticleController@store')->name('store.article');
// Route::get('/article/{id}','API\ArticleController@showArticle')->name('show.article');
// Route::post('/article/img-upload', 'ArticleController@storeImg')->name('storeImg'); 
// Route::post('/file-upload', 'FileController@upload');
?>

