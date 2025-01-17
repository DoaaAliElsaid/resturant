<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MealController;
use App\Http\Controllers\CheckAvailabilityController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TableController;

Route::get('tables/check_availability', [TableController::class, 'checkAvailability']);
Route::post('reservations', [ReservationController::class, 'store']);
Route::get('meals', [MealController::class, 'index']);
Route::post('orders', [OrderController::class, 'store']);
Route::post('orders/{order}/pay', [PaymentController::class, 'pay']);
// Route::middleware('auth')->group(function () {
//     Route::post('/orders', [OrderController::class, 'store']);
//     Route::post('/payments', [PaymentController::class, 'process']);
// });



