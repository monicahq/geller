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
    Volt::route('vaults/{vault}', 'vaults.show')->lazy()->name('vaults.show');
    Volt::route('vaults/{vault}/contacts/{contact}', 'contacts.show')->lazy()->name('contacts.show');
});
