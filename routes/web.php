<?php

use App\Http\Controllers\AppliedJobController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::get('login', [AuthController::class, 'index']);
Route::post('postLogin', [AuthController::class, 'login']);
Route::any('/register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth', 'UserManager']], function () {
    
    Route::any('dashboard', [DashboardController::class, 'dashboard']);

    Route::group(['prefix' => 'postJob'], function () {

        Route::any('/', [PostController::class, 'index']);
        Route::any('/add', [PostController::class, 'add']);
        Route::any('/edit/{id}', [PostController::class, 'edit']);
        Route::any('/delete', [PostController::class, 'delete']);
        Route::any('/view', [PostController::class, 'view']);
    });

    Route::any('profile', [ProfileController::class, 'profile']);
    Route::any('editProfile', [ProfileController::class, 'editProfile']);
    Route::any('apply', [ProfileController::class, 'applyJob']);
    Route::any('appliedJob', [AppliedJobController::class, 'appliedJob']);

    Route::any('jobView/{id}', [AppliedJobController::class, 'jobView']);
    Route::any('profileView/{id}', [DashboardController::class, 'profileView']);

    Route::get('logout', [AuthController::class, 'logout']);
});
