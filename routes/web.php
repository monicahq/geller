<?php

use App\Http\Controllers\Auth\TokenController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function (): void {
    Volt::route('login', 'auth.login')
        ->name('login');

    Route::get('/auth/callback', [TokenController::class, 'create'])->name('auth.token');
});
