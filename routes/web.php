<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
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

Route::get('/projects', [ProjectController::class,  'index'])->name('projects.index');
Route::post('/projects', [ProjectController::class,  'store'])->name('projects.store');
Route::put('/projects', [ProjectController::class,  'update'])->name('projects.update'); 
Route::delete('/projects/{project?}', [ProjectController::class,  'destroy'])->name('projects.destroy');// make task optional (temporarily) to route error

Route::get('/', [TaskController::class,  'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class,  'store'])->name('tasks.store');
Route::put('/tasks', [TaskController::class,  'update'])->name('tasks.update'); 
Route::put('/tasks/update-priority', [TaskController::class,  'updatePriority'])->name('tasks.updatePriority'); 
Route::delete('/tasks/{task?}', [TaskController::class,  'destroy'])->name('tasks.destroy'); // make task optional (temporarily) to avoid route error
