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

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::resource('/lists', 'ListController');
Route::prefix('/lists/{list}')->group(function() {
		Route::get('/image.jpg', 'ImageController@get')->name('lists.image');
		Route::resource('/tasks', 'TaskController');
		Route::post('/tasks/{task}', 'TaskController@completed')->name('tasks.completed');
		Route::resource('/shares', 'ShareController');
});


