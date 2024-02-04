<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('protected')->group(function () {

    /* Room routes */
    Route::post('/store-room', [RoomController::class, 'store']);
    Route::get('/view-room/{room}', [RoomController::class, 'show']);
    Route::put('/update-room/{room}', [RoomController::class, 'update']);
    Route::delete('/remove-room/{room}', [RoomController::class, 'remove']);

    /* Customer routes */
    Route::post('/store-customer', [CustomerController::class, 'store']);
    Route::put('/update-customer/{customer}', [CustomerController::class, 'update']);
    Route::get('/view-customer/{customer}', [CustomerController::class, 'index']);
    Route::delete('/remove-customer/{customer}', [CustomerController::class, 'remove']);

    /* Booking routes */
    Route::post('/store-booking', [BookingController::class, 'store']);
    Route::put('/update-booking/{booking}', [BookingController::class, 'update']);
    Route::get('/view-booking/{booking}', [BookingController::class, 'show']);
    Route::delete('/remove-booking/{booking}', [BookingController::class, 'remove']);

    /* Payment routes */
    Route::post('/store-payment', [PaymentController::class, 'store']);
    Route::put('/update-payment/{payment}', [PaymentController::class, 'update']);
    Route::get('/view-payment/{payment}', [PaymentController::class, 'show']);
    Route::delete('/remove-payment/{payment}', [PaymentController::class, 'remove']);
});
