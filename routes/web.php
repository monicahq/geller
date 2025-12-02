<?php

use App\Http\Controllers\Auth\TokenController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function (): void {
    Volt::route('login', 'auth.login')
        ->name('login');

    Route::get('auth/callback', TokenController::class)->name('auth.token');
});

Route::middleware('token')->group(function (): void {
    Volt::route('/', 'home')->name('home');
    Volt::route('vault/{vault}', 'vaults.show')->lazy()->name('vault.show');
    Volt::route('vault/{vault}/contact/{contact}', 'contacts.show')->lazy()->name('contact.show');
});
