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

Route::post('/register', [UserController::class, 'register'])->name('register');
Route::post('/login', [UserController::class, 'login'])->name('login');

Route::middleware('protected')->group(function () {

    /* Room routes */
    Route::post('/store-room', [RoomController::class, 'store'])->name('store-room');
    Route::get('/view-room/{room}', [RoomController::class, 'show'])->name('view-room');
    Route::put('/update-room/{room}', [RoomController::class, 'update'])->name('update-room');
    Route::delete('/remove-room/{room}', [RoomController::class, 'remove'])->name('remove-room');

    /* Customer routes */
    Route::post('/store-customer', [CustomerController::class, 'store'])->name('store-customer');
    Route::put('/update-customer/{customer}', [CustomerController::class, 'update'])->name('update-customer');
    Route::get('/view-customer/{customer}', [CustomerController::class, 'index'])->name('view-customer');
    Route::delete('/remove-customer/{customer}', [CustomerController::class, 'remove'])->name('remove-customer');

    /* Booking routes */
    Route::post('/store-booking', [BookingController::class, 'store'])->name('store-booking');
    Route::put('/update-booking/{booking}', [BookingController::class, 'update'])->name('update-booking');
    Route::get('/view-booking/{booking}', [BookingController::class, 'show'])->name('view-booking');
    Route::delete('/remove-booking/{booking}', [BookingController::class, 'remove'])->name('remove-booking');

    /* Payment routes */
    Route::post('/store-payment', [PaymentController::class, 'store'])->name('store-payment');
    Route::put('/update-payment/{payment}', [PaymentController::class, 'update'])->name('update-payment');
    Route::get('/view-payment/{payment}', [PaymentController::class, 'show'])->name('view-payment');
    Route::delete('/remove-payment/{payment}', [PaymentController::class, 'remove'])->name('remove-payment');
});
