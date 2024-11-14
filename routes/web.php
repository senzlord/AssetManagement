<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserListController;
use App\Http\Controllers\UserLogController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/users', [UserListController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserListController::class, 'create'])->name('users.create');
    Route::post('/users', [UserListController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserListController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{id}/restore', [UserListController::class, 'restore'])->name('users.restore');
    Route::delete('/users/{id}', [UserListController::class, 'destroy'])->name('users.destroy');

    Route::get('/users/{id}/change-access', [UserListController::class, 'changeAccess'])->name('users.changeAccess');
    Route::post('/users/{id}/update-access', [UserListController::class, 'updateAccess'])->name('users.updateAccess');

    Route::get('/logs', [UserLogController::class, 'index'])->name('logs.index');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('password.update');

});
