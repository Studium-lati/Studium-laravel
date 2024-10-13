<?php

use App\Http\Controllers\EventsController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ReservationsController;
use App\Http\Controllers\StadiumController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RandomMatchRequestsController;
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

    //reservationfor user
    Route::post('reservations/stadium', [ReservationsController::class, 'reserveStadium']); 




    //--------------------------------owner--------------------------------
    Route::get('stadiums/owner', [StadiumController::class, 'show']);
    Route::put('stadiums/owner/edit/{id}', [StadiumController::class, 'edit']);
    Route::put('stadiums/owner/changeStatus/{id}', [StadiumController::class, 'changeStatus']);






    Route::delete('reservations/delete/{id}', [ReservationsController::class, 'cancelReservation']);
    Route::put('reservations/changeStatus', [ReservationsController::class, 'changeStatus']);
    //--------------------------------admin--------------------------------
    Route::post('stadium/create', [StadiumController::class, 'create']);
    Route::get('users', [UserController::class, 'index']);

    Route::delete('stadiums/delete/{id}', [StadiumController::class, 'destroy']);



    //----------------------------------------reservation
    Route::get('reservations', [ReservationsController::class, 'index']);
    Route::get('reservations/status', [ReservationsController::class, 'reservationStatus']);

    Route::get('reservations/viewReservations', [ReservationsController::class, 'viewReservations']);



    //--------------------------------------random match request--------------------------------
    Route::post('random-match-request', [RandomMatchRequestsController::class, 'requestRandomMatch']);

    Route::put('random-match-request/cancel', [RandomMatchRequestsController::class, 'cancel']);




    //-------------------------------------events--------------------------------
    Route::get('events', [EventsController::class, 'index']);
    Route::get('events/{id}', [EventsController::class, 'show']);
    Route::post('events/create', [EventsController::class, 'create']);

    Route::put('events/edit/{id}', [EventsController::class, 'edit']);

    Route::delete('events/delete/{id}', [EventsController::class, 'destroy']);

    Route::put('events/changeStatus/{id}', [EventsController::class, 'changeStatus']);

    //-------------------------------------feedback--------------------------------

    Route::get('feedbacks/{id}', [FeedbackController::class, 'index']);
    Route::post('feedbacks/create', [FeedbackController::class, 'create']);
    Route::get('feedbacks/averageRating/{id}', [FeedbackController::class, 'averageRating']);

});
