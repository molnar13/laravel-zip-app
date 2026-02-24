<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZipCodeController;

// A főoldal a mi alkalmazásunk
Route::get('/', [ZipCodeController::class, 'index'])->name('zipcodes.index');

// A bejelentkezés után is a mi alkalmazásunk töltődjön be a felesleges "Dashboard" helyett!
Route::get('/dashboard', [ZipCodeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // A mi Irányítószámos útvonalaink
    Route::get('/zipcodes/{zipCode}/edit', [ZipCodeController::class, 'edit'])->name('zipcodes.edit');
    Route::put('/zipcodes/{zipCode}', [ZipCodeController::class, 'update'])->name('zipcodes.update');
    Route::get('/export-csv', [ZipCodeController::class, 'exportCsv'])->name('zipcodes.csv');
    Route::get('/export-pdf', [ZipCodeController::class, 'exportPdf'])->name('zipcodes.pdf');
    Route::post('/send-email', [ZipCodeController::class, 'sendEmail'])->name('zipcodes.email');

    // Profil útvonalak
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';