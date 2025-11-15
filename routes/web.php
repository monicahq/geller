<?php

use App\Http\Controllers\Auth\TokenController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function (): void {
    Volt::route('login', 'auth.login')
        ->name('login');

    Route::get('/auth/callback', [TokenController::class, 'create'])->name('auth.token');
});

Route::middleware('token')->group(function (): void {
    Route::get('/dashboard', function () {
        return 'logged';
    })->name('dashboard');
});

Route::get('/', function () {
    return redirect('login');
});
