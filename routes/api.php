<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'login'])->name('api.user.login');

Route::post('/register', [UserController::class, 'register'])->name('api.user.register');

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/profile', [UserController::class, 'profile'])->name('api.user.profile');

    Route::get('/logout', [UserController::class, 'logout'])->name('api.user.logout');

});

