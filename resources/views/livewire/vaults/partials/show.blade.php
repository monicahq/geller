<?php

use App\Models\Vault;

use function Livewire\Volt\mount;
use function Livewire\Volt\state;

state(['vault'])->locked();

mount(function (Vault $vault) {
    $this->vault = $vault;
});
?>

<div>
    <x-link href="{{ route('home') }}">
      <div class="flex items-center gap-2">
        <x-phosphor-caret-left class="size-4 min-w-3" />
        <span>
          {{ __('Home') }}
        </span>
      </div>
    </x-link>

    <h1 class="text-2xl font-bold mb-4">
      {{ $vault->name }}
    </h1>

    <livewire:contacts.lazy.index :$vault lazy />
</div>
