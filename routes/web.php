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


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});



 Route::get('/test/', 'TestController@show');


//Route::get('/pages/show/{id}/', 'page@showAll')->where('id', '[0-9]+');


// Route::get('/test/sum/{num1}/{num2}/', 'test@sum')->where(['num1' => '[0-9]+', 'num2' => '[0-9]+']);

// Route::get('/test/{i}', 'Employee@showOne');

// Route::get('/test/{i}/{name}', 'Employee@showField')->where(['i' => '[0-9]+', 'name' => '[A_Za-z0-9]+']);

Route::get('/animals','AjaxController@index');
// Route::get('/gender','AjaxController@gender_page');
Route::get('/constructor_page','AjaxController@constructor_page');
// Route::get('/parts','AjaxController@parts');
// Route::get('/image','AjaxController@image');

Route::get('/get_lang','AjaxController@get_lang');

Route::get('/get_petBackgrounds','AjaxController@get_petBackgrounds');