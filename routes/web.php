<?php

namespace App\Http\Controllers;

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

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', function () {
        return Inertia::render('Index');
    })->name('index');

    // Смешанный доступ
    Route::get('/export/{user}', [ExportController::class, 'index'])->name('export.index');
    Route::get('/export-launch/{user}', [ExportController::class, 'export'])->name('export.launch');

    // Администратор
    Route::group([
        'middleware' => 'is_admin',
        'as' => 'admin.'
    ], function () {
        Route::get('control-panel/create', [UserController::class, 'create'])->name('control-panel.create');
        Route::post('control-panel', [UserController::class, 'store'])->name('control-panel.store');
        Route::get('control-panel/{user}/edit', [UserController::class, 'edit'])->name('control-panel.edit');
        Route::put('control-panel/{user}', [UserController::class, 'update'])->name('control-panel.update');
        Route::delete('control-panel/{user}', [UserController::class, 'destroy'])->name('control-panel.destroy');
        Route::put('control-panel/{user}/restore', [UserController::class, 'restore'])->name('control-panel.restore');
    });

    // Руководитель
    Route::group([
        'middleware' => 'is_lead',
        'as' => 'admin.'
    ], function () {
        Route::get('control-panel', [UserController::class, 'index'])->name('control-panel.index');

        Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
        Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
        Route::put('projects/{project}/restore', [ProjectController::class, 'restore'])->name('projects.restore');

        Route::get('projects/{project}/invite', [ProjectController::class, 'inviteToProject'])->name('projects.invite');
        Route::post('projects/{project}/invite-store', [ProjectController::class, 'inviteStore'])->name('projects.invite-store');

        Route::group([
            'prefix' => 'projects/{project}',
            'as' => 'projects.'
        ], function () {
            Route::get('tasks/{task}/invite', [TaskController::class, 'inviteToTask'])->name('tasks.invite');
            Route::post('tasks/{task}/invite-store', [TaskController::class, 'inviteStore'])->name('tasks.invite-store');
        });
    });

    // Пользователь
    Route::group([
        'as' => 'user.'
    ], function () {
        Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');

        Route::group([
            'prefix' => 'projects/{project}',
            'as' => 'projects.'
        ], function () {
            Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
            Route::get('tasks/create', [TaskController::class, 'create'])->name('tasks.create');
            Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
            Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
            Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
            Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
            Route::put('tasks/{task}/restore', [TaskController::class, 'restore'])->name('tasks.restore');
        });
    });

});

require __DIR__ . '/auth.php';
