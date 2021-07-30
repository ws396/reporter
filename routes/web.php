<?php

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

        Route::get('tasklist', [App\Http\Controllers\User\TaskController::class, 'index'])->name('tasklist');

    });


    Route::group([
        'prefix' => 'user',
        'as' => 'user.'
    ], function () {

        Route::get('tasks', [App\Http\Controllers\User\TaskController::class, 'index'])->name('tasks');
        Route::get('tasks/create', [App\Http\Controllers\User\TaskController::class, 'create'])->name('tasks.create');
        Route::post('tasks', [App\Http\Controllers\User\TaskController::class, 'store'])->name('tasks.store');
        Route::get('tasks/{task}/edit', [App\Http\Controllers\User\TaskController::class, 'edit'])->name('tasks.edit');
        Route::put('tasks/{task}', [App\Http\Controllers\User\TaskController::class, 'update'])->name('tasks.update');
        Route::delete('tasks/{task}', [App\Http\Controllers\User\TaskController::class, 'destroy'])->name('tasks.destroy');
        Route::put('tasks/{task}/restore', [App\Http\Controllers\User\TaskController::class, 'restore'])->name('tasks.restore');

    });

});

require __DIR__ . '/auth.php';
