<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

/*
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
*/

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', function () {
        return Inertia::render('Index');
    })->name('index');

    Route::group([
        'prefix' => 'admin',
        'middleware' => 'is_admin',
        'as' => 'admin.'
    ], function () {

        Route::get('control-panel', [Admin\UserController::class, 'index'])->name('control-panel');
        Route::get('control-panel/create', [Admin\UserController::class, 'create'])->name('control-panel.create');
        Route::post('control-panel', [Admin\UserController::class, 'store'])->name('control-panel.store');
        Route::get('control-panel/{user}/edit', [Admin\UserController::class, 'edit'])->name('control-panel.edit');
        Route::put('control-panel/{user}', [Admin\UserController::class, 'update'])->name('control-panel.update');
        Route::delete('control-panel/{user}', [Admin\UserController::class, 'destroy'])->name('control-panel.destroy');
        Route::put('control-panel/{user}/restore', [Admin\UserController::class, 'restore'])->name('control-panel.restore');

    });


    Route::group([
        'prefix' => 'user',
        'as' => 'user.'
    ], function () {

        Route::get('projects', [User\ProjectController::class, 'index'])->name('projects');
        Route::get('projects/create', [User\ProjectController::class, 'create'])->name('projects.create');
        Route::post('projects', [User\ProjectController::class, 'store'])->name('projects.store');
        Route::get('projects/{project}/edit', [User\ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('projects/{project}', [User\ProjectController::class, 'update'])->name('projects.update');
        Route::delete('projects/{project}', [User\ProjectController::class, 'destroy'])->name('projects.destroy');
        Route::put('projects/{project}/restore', [User\ProjectController::class, 'restore'])->name('projects.restore');

        Route::group([
            'prefix' => 'projects/{project}',
            'as' => 'projects.'
        ], function () {
            Route::get('tasks', [User\TaskController::class, 'index'])->name('tasks');
            Route::get('tasks/create', [User\TaskController::class, 'create'])->name('tasks.create');
            Route::post('tasks', [User\TaskController::class, 'store'])->name('tasks.store');
            Route::get('tasks/{task}/edit', [User\TaskController::class, 'edit'])->name('tasks.edit');
            Route::put('tasks/{task}', [User\TaskController::class, 'update'])->name('tasks.update');
            Route::delete('tasks/{task}', [User\TaskController::class, 'destroy'])->name('tasks.destroy');
            Route::put('tasks/{task}/restore', [User\TaskController::class, 'restore'])->name('tasks.restore');
        });

    });

});

require __DIR__ . '/auth.php';
