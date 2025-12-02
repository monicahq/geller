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

    return redirect()->route('vault.show', $vault);
};
?>

<div>
  <h2 class="text-xl font-semibold mb-2">{{ __('Your Vaults:') }}</h2>
  <div class="list-disc list-inside mb-6">
    <livewire:vaults.lazy.index lazy />
  </div>

  <x-box
    x-data="{
    showForm: false,
    toggle: function() { this.showForm = !this.showForm }
  }">
    <div class="flex justify-center" x-show="!showForm" x-transition:enter.duration.200ms>
      <flux:button icon="plus-circle" @click.prevent="toggle()">{{ __('Add a vault') }}</flux:button>
    </div>

    <!-- create vault form -->
    <form method="POST" wire:submit="create" class="space-y-6" wire:cloak x-show="showForm" x-transition:enter.duration.200ms>
      <x-input wire:model="name" id="name" :label="__('Vault name')" type="text" required />
      <x-input wire:model="description" id="description" :label="__('Vault description')" type="text" :optional="true" />

      <div class="flex items-center justify-between">
        <flux:button variant="filled" @click.prevent="toggle()">
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
  </x-box>
</div>
