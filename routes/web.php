<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\AdminDashboardController;
use App\Http\Controllers\Web\PostController;
use App\Http\Controllers\Web\InvestmentRateController;
use App\Http\Controllers\Web\SupportMessageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login', 302);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'admin'])->name('dashboard');

Route::get('/coming-soon', function () {
    return view('coming-soon');
})->middleware('auth')->name('coming-soon');

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

    // Biblioteca
    Route::get('/biblioteca', [PostController::class, 'index'])->name('biblioteca.index');
    Route::get('/get-posts', [PostController::class, 'getPosts'])->name('biblioteca.get-posts');
    Route::get('/biblioteca/create', [PostController::class, 'create'])->name('biblioteca.create');
    Route::post('/biblioteca', [PostController::class, 'store'])->name('biblioteca.store');
    Route::get('/biblioteca/{post}/edit',[PostController::class, 'edit'])->name('biblioteca.edit');
    Route::put('/biblioteca/', [PostController::class, 'update'])->name('biblioteca.update');
    Route::patch('/biblioteca/{post}/toggle-publish', [PostController::class, 'togglePublish'])->name('biblioteca.toggle-publish');
    Route::delete('/biblioteca/{post}', [PostController::class, 'destroy'])->name('biblioteca.destroy');

    // Tasas de inversión
    Route::get('/tasas', [InvestmentRateController::class, 'index'])->name('tasas.index');
    Route::get('/get-rates', [InvestmentRateController::class, 'getRates'])->name('tasas.get-rates');
    Route::post('/tasas', [InvestmentRateController::class, 'store'])->name('tasas.store');
    Route::get('/tasas/{id_rate}', [InvestmentRateController::class, 'getRate'])->name('tasas.get-rate');
    Route::put('/tasas', [InvestmentRateController::class, 'update'])->name('tasas.update');
    Route::delete('/tasas/{rate}', [InvestmentRateController::class, 'destroy'])->name('tasas.destroy');

    // Buzón
    Route::get('/buzon', [SupportMessageController::class, 'index'])->name('buzon.index');
    Route::get('/get-buzon', [SupportMessageController::class, 'getBuzon'])->name('buzon.get-buzon');
    Route::get('/buzon/{message}', [SupportMessageController::class, 'show'])->name('buzon.show');
    Route::patch('/buzon/{message}/resolve', [SupportMessageController::class, 'toggleResolve'])->name('buzon.resolve');
    Route::delete('/buzon/{message}', [SupportMessageController::class, 'destroy'])->name('buzon.destroy');
});




require __DIR__.'/auth.php';
