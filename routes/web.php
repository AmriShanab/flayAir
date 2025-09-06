<?php

use App\Http\Controllers\Admin\WorkerController as AdminWorkerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthCountroller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\WorkerController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::get('/register', [AuthCountroller::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthCountroller::class, 'register'])->name('register.post');

Route::get('/login', [AuthCountroller::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthCountroller::class, 'login'])->name('login.post');

Route::post('/logout', [AuthCountroller::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
    Route::get('/flights/data', [ShiftController::class, 'getFlightsForDate'])->name('flights.data');
    Route::get('/shifts/data', [ShiftController::class, 'getShiftsForDate'])->name('flights.data');
});

// Admin Routes
Route::middleware(['auth', RoleMiddleware::class.':admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

    // Workers CRUD
    Route::resource('workers', \App\Http\Controllers\Admin\WorkerController::class);

    // Shifts & Flights
    Route::get('/add/shifts', [AdminController::class, 'addShifts'])->name('admin.add.shifts');
    Route::get('/add/flights', [AdminController::class, 'addFlights'])->name('admin.add.flights');
    Route::post('/add/store/shifts', [AdminController::class, 'storeShifts'])->name('admin.store.shifts');
    Route::post('/add/store/flights', [AdminController::class, 'storeFlights'])->name('admin.store.flights');
});


// Super Admin Routes
Route::middleware(['auth', RoleMiddleware::class.':super_admin'])->prefix('super-admin')->group(function () {
    Route::get('/', [SuperAdminController::class, 'index'])->name('super.dashboard');
});
