<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login\LoginController as UserLoginController;
use App\Http\Controllers\Admin\Login\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Middleware\AdminMiddleware;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', [UserLoginController::class, 'index']);



Route::get('/admin/login', [AdminLoginController::class, 'index'])->name('admin.login');
Route::post('/admin/login',[AdminLoginController::class, 'login'])->name('admin.login');
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'listUser'])->name('users');
});


Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    Route::middleware(['admin'])->group(function () {
        Route::resource('clients', UserController::class);
    });
});