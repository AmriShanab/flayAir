<?php

use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
Route::get('/flights', [ShiftController::class, 'flightIndex'])->name('shifts.index');
Route::get('/shifts/data', [ShiftController::class, 'getShiftsForDate'])->name('shifts.data');
Route::post('/shifts', [ShiftController::class, 'store'])->name('shifts.store');
Route::put('/shifts/{shift}', [ShiftController::class, 'update'])->name('shifts.update');
Route::delete('/shifts/{shift}', [ShiftController::class, 'destroy'])->name('shifts.destroy');
Route::get('/flights/data', [ShiftController::class, 'getFlightsForDate'])->name('flights.data');