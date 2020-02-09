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

Auth::routes();

Route::get('/tasks', 'TaskController@index')->name('home');
Route::post('/tasks', 'TaskController@addTask')->name('add_task');

Route::get('/tasks/{id}', 'TaskController@task')->name('task');
Route::post('/tasks/{id}', 'TaskController@editTask')->name('edit_task');
Route::post('/tasks/{id}/complete', 'TaskController@completeTask')->name('complete_task');
Route::post('/tasks/{id}/delete', 'TaskController@deleteTask')->name('delete_task');


