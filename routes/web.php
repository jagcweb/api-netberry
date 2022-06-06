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

Route::prefix('api/v1')->group(function () {

    //UserController
    Route::post('/register', [App\Http\Controllers\UserController::class, 'register'])->name('user.register');
    Route::post('/login', [App\Http\Controllers\UserController::class, 'login'])->name('user.login');
    Route::get('/get/{token}', [App\Http\Controllers\UserController::class, 'get'])->name('user.get');

    //TaskController
    Route::post('/create', [App\Http\Controllers\TaskController::class, 'create'])->name('task.create');
    Route::get('/get-tasks/{token}', [App\Http\Controllers\TaskController::class, 'get'])->name('task.get');
    Route::put('/update/{id}', [App\Http\Controllers\TaskController::class, 'update'])->name('task.update');
    Route::delete('/delete/{id}', [App\Http\Controllers\TaskController::class, 'delete'])->name('task.delete');
});
