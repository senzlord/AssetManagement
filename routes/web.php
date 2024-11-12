<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserListController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/users', [UserListController::class, 'index'])->name('users.index')->middleware('auth');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile')->middleware('auth');
Route::get('/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('password.change')->middleware('auth');
Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('password.update')->middleware('auth');
