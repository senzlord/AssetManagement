<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserListController;
use App\Http\Controllers\UserLogController;
use App\Http\Controllers\SFPController;
use App\Http\Controllers\HardwareController;
use App\Http\Controllers\NonHardwareController;

Route::get('/', function () {
    return redirect()->route('login');
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
    Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('password.update.submit');

    Route::prefix('sfp')->group(function () {
        Route::get('/', [SFPController::class, 'sfpIndex'])->name('sfp.index');
        Route::get('/export', [SFPController::class, 'exportSfp'])->name('sfp.export');
        Route::get('/create', [SFPController::class, 'createSfp'])->name('sfp.create');
        Route::post('/', [SFPController::class, 'storeSfp'])->name('sfp.store');
        Route::get('/{id}', [SFPController::class, 'showSfp'])->name('sfp.show');
        Route::get('/{id}/edit', [SFPController::class, 'editSfp'])->name('sfp.edit');
        Route::put('/{id}', [SFPController::class, 'updateSfp'])->name('sfp.update');
    });

    Route::prefix('hardware')->group(function () {
        Route::get('/', [HardwareController::class, 'index'])->name('hardware.index');
        Route::get('/export', [HardwareController::class, 'export'])->name('hardware.export');
        Route::get('/create', [HardwareController::class, 'create'])->name('hardware.create');
        Route::post('/', [HardwareController::class, 'store'])->name('hardware.store');
        Route::get('/{id}', [HardwareController::class, 'show'])->name('hardware.show');
        Route::get('/{id}/edit', [HardwareController::class, 'edit'])->name('hardware.edit');
        Route::put('/{id}', [HardwareController::class, 'update'])->name('hardware.update');

        Route::post('/category', [HardwareController::class, 'storeCategory'])->name('hardware.category.store');
    });

    Route::prefix('nonhardware')->group(function () {
        Route::get('/', [NonHardwareController::class, 'index'])->name('nonhardware.index');
        Route::get('/export', [NonHardwareController::class, 'export'])->name('nonhardware.export');
        Route::get('/create', [NonHardwareController::class, 'create'])->name('nonhardware.create');
        Route::post('/', [NonHardwareController::class, 'store'])->name('nonhardware.store');
        Route::get('/{id}', [NonHardwareController::class, 'show'])->name('nonhardware.show');
        Route::get('/{id}/edit', [NonHardwareController::class, 'edit'])->name('nonhardware.edit');
        Route::put('/{id}', [NonHardwareController::class, 'update'])->name('nonhardware.update');

        Route::post('/category', [NonHardwareController::class, 'storeCategory'])->name('nonhardware.category.store');
    });
});
