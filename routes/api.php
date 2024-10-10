<?php

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [UserController::class, 'store']);
Route::post('login', [UserController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('profile', [UserController::class, 'profile']);
    Route::put('profile/update', [UserController::class, 'update']);
    Route::delete('profile/delete', [UserController::class, 'destroy']);
    Route::get('users/{id}', [UserController::class, 'show']);


    
    Route::get('users', [UserController::class, 'index']);
});
