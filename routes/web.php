<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Guest\GuestDashboardController;
use App\Http\Controllers\Guest\GuestRoomController;
use App\Http\Controllers\Guest\GuestReservationController;
use App\Http\Controllers\Guest\GuestInvoiceController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Admin Dashboard Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Room Management
    Route::prefix('rooms')->group(function () {
        Route::get('/', [RoomController::class, 'index'])->name('rooms.index');
        Route::get('/create', [RoomController::class, 'create'])->name('rooms.create');
        Route::post('/', [RoomController::class, 'store'])->name('rooms.store');
        Route::get('/{room}', [RoomController::class, 'show'])->name('rooms.show');
        Route::get('/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
        Route::put('/{room}', [RoomController::class, 'update'])->name('rooms.update');
        Route::delete('/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');
    });
    
    // Reservation Management
    Route::prefix('reservations')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('reservations.index');
        Route::get('/create', [ReservationController::class, 'create'])->name('reservations.create');
        Route::post('/', [ReservationController::class, 'store'])->name('reservations.store');
        Route::get('/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
        Route::get('/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
        Route::put('/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
        Route::post('/{reservation}/check-in', [ReservationController::class, 'checkIn'])->name('reservations.check-in');
        Route::post('/{reservation}/check-out', [ReservationController::class, 'checkOut'])->name('reservations.check-out');
        Route::delete('/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
    });
    
    // Invoice Management
    Route::prefix('invoices')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    });
});

// Guest Routes
Route::middleware('auth')->prefix('guest')->group(function () {
    Route::get('/dashboard', [GuestDashboardController::class, 'index'])->name('guest.dashboard');
    
    // Room Browsing
    Route::get('/rooms', [GuestRoomController::class, 'index'])->name('guest.rooms.index');
    Route::get('/rooms/{room}', [GuestRoomController::class, 'show'])->name('guest.rooms.show');
    
    // Guest Reservations
    Route::prefix('reservations')->group(function () {
        Route::get('/', [GuestReservationController::class, 'index'])->name('guest.reservations.index');
        Route::get('/create', [GuestReservationController::class, 'create'])->name('guest.reservations.create');
        Route::post('/', [GuestReservationController::class, 'store'])->name('guest.reservations.store');
        Route::get('/{reservation}', [GuestReservationController::class, 'show'])->name('guest.reservations.show');
        Route::post('/{reservation}/cancel', [GuestReservationController::class, 'cancel'])->name('guest.reservations.cancel');
    });
    
    // Guest Invoices
    Route::prefix('invoices')->group(function () {
        Route::get('/', [GuestInvoiceController::class, 'index'])->name('guest.invoices.index');
        Route::get('/{invoice}', [GuestInvoiceController::class, 'show'])->name('guest.invoices.show');
        Route::get('/{invoice}/download', [GuestInvoiceController::class, 'download'])->name('guest.invoices.download');
    });
});

// Default authenticated dashboard redirect
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('guest.dashboard');
    })->name('dashboard');
});
