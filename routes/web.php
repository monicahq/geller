<?php

use App\Http\Controllers\Auth\TokenController;
use App\Models\Instance;
use App\Models\User;
use App\Services\GetUser;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function (): void {
    Volt::route('login', 'auth.login')
        ->name('login');

    Route::get('auth/callback', TokenController::class)->name('auth.token');
});

Route::middleware('token')->group(function (): void {
    Route::get('/', function () {

        $user = User::first();

        if ($user === null) {
            $user = (new GetUser())->store();
        }

        return "User: {$user->name}";
    })->name('home');
});
