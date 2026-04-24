<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas del Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard-stats', [AdminDashboardController::class, 'getStats'])->name('get-stats');
    Route::get('/dashboard-daily-interaction', [AdminDashboardController::class, 'getDailyInteraction'])->name('get-daily.interactions');
    Route::get('/dashboard-last-users-registers', [AdminDashboardController::class, 'getLastUsersRegister'])->name('last-users-registers');
    
});




require __DIR__.'/auth.php';
