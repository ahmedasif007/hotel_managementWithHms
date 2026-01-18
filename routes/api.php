<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DashboardController;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/health', [\App\Http\Controllers\HealthController::class, 'check']);
Route::get('/health/detailed', [\App\Http\Controllers\HealthController::class, 'detailed']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Dashboard routes
    Route::get('/dashboard/statistics', [DashboardController::class, 'statistics']);
    Route::get('/dashboard/recent-reservations', [DashboardController::class, 'recentReservations']);
    Route::get('/dashboard/recent-payments', [DashboardController::class, 'recentPayments']);
    Route::get('/dashboard/revenue', [DashboardController::class, 'revenue']);

    // Room routes
    Route::apiResource('rooms', RoomController::class);
    Route::get('/rooms/availability', [RoomController::class, 'availability']);

    // Guest routes
    Route::apiResource('guests', GuestController::class);

    // Reservation routes
    Route::apiResource('reservations', ReservationController::class);
    Route::post('/reservations/{id}/check-in', [ReservationController::class, 'checkIn']);
    Route::post('/reservations/{id}/check-out', [ReservationController::class, 'checkOut']);
    Route::post('/reservations/{id}/cancel', [ReservationController::class, 'cancel']);

    // Billing routes
    Route::post('/invoices/create/{reservationId}', [BillingController::class, 'createInvoice']);
    Route::get('/invoices/{id}', [BillingController::class, 'getInvoice']);
    Route::get('/invoices', [BillingController::class, 'listInvoices']);
    Route::post('/payments', [BillingController::class, 'recordPayment']);
});
