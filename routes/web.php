<?php

use App\Http\Controllers\AuthCountroller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return view('welcome');
});




Route::get('/register', [AuthCountroller::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthCountroller::class, 'register'])->name('register.post');

Route::get('/login', [AuthCountroller::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthCountroller::class, 'login'])->name('login.post');

Route::post('/logout', [AuthCountroller::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
    // Route::get('/flights', [ShiftController::class, 'flightIndex'])->name('shifts.index');
    // Route::get('/shifts/data', [ShiftController::class, 'getShiftsForDate'])->name('shifts.data');
    // Route::post('/shifts', [ShiftController::class, 'store'])->name('shifts.store');
    // Route::put('/shifts/{shift}', [ShiftController::class, 'update'])->name('shifts.update');
    // Route::delete('/shifts/{shift}', [ShiftController::class, 'destroy'])->name('shifts.destroy');
    Route::get('/flights/data', [ShiftController::class, 'getFlightsForDate'])->name('flights.data');
});
