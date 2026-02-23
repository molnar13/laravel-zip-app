<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZipCodeController;

Route::get('/', [ZipCodeController::class, 'index'])->name('zipcodes.index');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/export-csv', [ZipCodeController::class, 'exportCsv'])->name('zipcodes.csv');
    Route::get('/export-pdf', [ZipCodeController::class, 'exportPdf'])->name('zipcodes.pdf');
    Route::post('/send-email', [ZipCodeController::class, 'sendEmail'])->name('zipcodes.email');
    
    // Ide jöhetnek a módosító (edit, update) route-ok is
});

require __DIR__.'/auth.php';
