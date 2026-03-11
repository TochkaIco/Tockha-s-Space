<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\StepController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskImageController;
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
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('task.store');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');

    Route::patch('/steps/{step}', [StepController::class, 'update'])->name('step.update');

    Route::middleware('can:workWith,task')->group(function () {
        Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('task.show');
        Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('task.update');

        Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('task.destroy');
        Route::delete('tasks/{task}/image', [TaskImageController::class, 'destroy'])->name('task.image.destroy');
    });

    Route::post('/logout', [SessionsController::class, 'destroy']);
});
