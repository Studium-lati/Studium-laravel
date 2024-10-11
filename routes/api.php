<?php

use App\Http\Controllers\StadiumController;
use App\Http\Controllers\UserController;
use App\Models\Stadium;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [UserController::class, 'create']);
Route::post('login', [UserController::class, 'login']);
Route::put('reset-password', [UserController::class, 'resetPassword']);
Route::middleware('auth:api')->group(function () {
    //user
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('profile', [UserController::class, 'profile']);
    Route::put('profile/update', [UserController::class, 'update']);
    Route::delete('profile/delete', [UserController::class, 'destroy']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('user/updatepassword', [UserController::class, 'updatePassword']);


 Route::get('stadiums/search', [StadiumController::class, 'showStadium']);
    Route::get('stadiums', [StadiumController::class, 'index']);
    //--------------------------------owner--------------------------------
    Route::get('stadiums/owner', [StadiumController::class, 'show']);
    Route::put('stadiums/owner/edit/{id}', [StadiumController::class, 'edit']);
    Route::put('stadiums/owner/changeStatus/{id}', [StadiumController::class, 'changeStatus']);
    


    //--------------------------------admin--------------------------------
    Route::post('stadium/create', [StadiumController::class, 'create']);
    Route::get('users', [UserController::class, 'index']);

    Route::delete('stadiums/delete/{id}', [StadiumController::class, 'destroy']);
});
