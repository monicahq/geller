<?php

use App\Services\CreateVault;

use function Livewire\Volt\state;

state(['name', 'description']);

$create = function() {
    $validated = $this->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
    ]);

    $vault = (new CreateVault)->store($validated);

    $this->reset(['name', 'description']);

    $this->dispatch('vault-created');

    return redirect()->route('vaults.show', $vault);
};

$back = function() {
    xdebug_break();
    return back();
};
?>

<div class="w-full max-w-96 mx-auto p-4">
  <h2 class="text-xl font-semibold mb-2">{{ __('Create a vault') }}</h2>

  <!-- create vault form -->
  <form method="POST" wire:submit="create" class="space-y-6">
    <x-input wire:model="name" id="name" :label="__('Vault name')" type="text" required />
    <x-input wire:model="description" id="description" :label="__('Vault description')" type="text" :optional="true" />

    <div class="flex items-center justify-between">
      <flux:button variant="filled" x-on:click.prevent="$wire.back()">
        {{ __('Cancel') }}
      </flux:button>

      <div class="flex items-center gap-4">
        <div class="flex items-center justify-end">
          <flux:button variant="primary" type="submit" class="w-full">
            {{ __('Create') }}
          </flux:button>
        </div>

        <x-action-message class="me-3" on="vault-created">
          {{ __('Created.') }}
        </x-action-message>
      </div>
    </div>
  </form>
</div>
