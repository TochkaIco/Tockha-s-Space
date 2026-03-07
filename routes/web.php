<?php

use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function (): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create']);
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/login', [SessionsController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('task.show');

    Route::post('/logout', [SessionsController::class, 'destroy']);
});
