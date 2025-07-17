<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BureauController;
use App\Http\Controllers\ReservationController;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/bureaus', [BureauController::class, 'index']);

Route::middleware('auth:api')->group(function () {
    Route::post('/reservation', [ReservationController::class, 'store']);
    Route::get('/users/{id}/reservations', [ReservationController::class, 'fetchUserReservations']);
});
