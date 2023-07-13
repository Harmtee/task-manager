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

Route::get('/', 'TaskController@index')->name('tasks.index');
Route::post('/tasks', 'TaskController@store')->name('tasks.store');
Route::put('/tasks/update-priority', 'TaskController@updatePriority')->name('tasks.updatePriority'); 
Route::get('/tasks/{task}/edit', 'TaskController@edit')->name('tasks.edit');
Route::put('/tasks/{task?}', 'TaskController@update')->name('tasks.update'); // make task optional (temporarily) to avoid js error
Route::delete('/tasks/{task?}', 'TaskController@destroy')->name('tasks.destroy'); // make task optional (temporarily) to avoid js error

Route::get('/projects', 'ProjectController@index')->name('projects.index');
Route::post('/projects', 'ProjectController@store')->name('projects.store');
Route::get('/projects/edit/{project?}', 'ProjectController@edit')->name('projects.edit'); // make project optional (temporarily) to avoid js error
Route::put('/projects/{project?}', 'ProjectController@update')->name('projects.update'); // make project optional (temporarily) to avoid js error
Route::delete('/projects/{project?}', 'ProjectController@destroy')->name('projects.destroy'); // make project optional (temporarily) to avoid js error
