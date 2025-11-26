<?php

use App\Models\User;
use App\Services\GetUser;
use App\Services\SyncVaults;

use function Livewire\Volt\mount;
use function Livewire\Volt\state;

state(['user','vaults']);

mount(function () {
    $this->user = User::first() ?? (new GetUser())->store();

    $this->vaults = (new SyncVaults)();
});
?>

<div>
    <h1 class="text-2xl font-bold mb-4">
      {{ __('Welcome, :name!', ['name' => $user->name]) }}
    </h1>

    <h2 class="text-xl font-semibold mb-2">{{ __('Your Vaults:') }}</h2>
    <div class="list-disc list-inside mb-6">
        @foreach ($vaults as $vault)
            <div class="mb-4">
                <x-link href="{{ route('vault.show', $vault) }}">
                    {{ $vault->name }}
                </x-link>
            </div>
        @endforeach
    </div>
</div>
