<?php

use App\Models\Vault;
use App\Services\GetVault;
use App\Services\SyncContacts;

use function Livewire\Volt\mount;
use function Livewire\Volt\state;

state(['vault']);

mount(function (Vault $vault) {
    $this->vault = $vault;
});
?>

<div>
    <h1 class="text-2xl font-bold mb-4">{{ $vault->name }}</h1>

    <livewire:contacts.lazy.index :$vault lazy />

    <x-link href="{{ route('home') }}">
      {{ __('Back') }}
    </x-link>
</div>
