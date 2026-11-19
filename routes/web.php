<?php

use App\Http\Controllers\AdminSupportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DiscoverController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupportController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegistration'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('role:user')->group(function (): void {
        Route::get('/discover', [DiscoverController::class, 'index'])->name('discover.index');
        Route::post('/discover/{target}', [DiscoverController::class, 'action'])->name('discover.action');

        Route::get('/matches', [MatchController::class, 'index'])->name('matches.index');
        Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
        Route::get('/chats/{match}', [ChatController::class, 'show'])->name('chats.show');
        Route::post('/chats/{match}/messages', [MessageController::class, 'store'])->name('messages.store');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('/support', [SupportController::class, 'index'])->name('support.index');
        Route::post('/support', [SupportController::class, 'store'])->name('support.store');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/support', [AdminSupportController::class, 'index'])->name('support.index');
        Route::patch('/support/{supportRequest}', [AdminSupportController::class, 'update'])->name('support.update');
    });
});
