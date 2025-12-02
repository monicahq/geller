<?php

use App\Models\Vault;
use App\Services\CreateContact;

use function Livewire\Volt\mount;
use function Livewire\Volt\state;

state(['vault'])->locked();
state(['name']);

mount(function (Vault $vault) {
    $this->vault = $vault;
});

$create = function() {
  $validated = $this->validate([
    'name' => 'required|string|max:255',
  ]);

  $contact = (new CreateContact($this->vault))->store($validated);

  $this->reset(['name']);

  $this->dispatch('contact-created');

  return redirect()->route('contact.show', [$this->vault, $contact]);
};
?>

<div>
  <livewire:contacts.lazy.index :$vault lazy />

  <x-box
    x-data="{
    showForm: false,
    toggle: function() { this.showForm = !this.showForm }
  }">
    <div class="flex justify-center" x-show="!showForm" x-transition:enter.duration.200ms>
      <flux:button icon="plus-circle" @click.prevent="toggle()">{{ __('Add a contact') }}</flux:button>
    </div>

    <!-- create contact form -->
    <form method="POST" wire:submit="create" class="space-y-6" wire:cloak x-show="showForm" x-transition:enter.duration.200ms>
      <x-input wire:model="name" id="name" :label="__('Contact name')" type="text" required />

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

          <x-action-message class="me-3" on="contact-created">
            {{ __('Created.') }}
          </x-action-message>
        </div>
      </div>
    </form>
  </x-box>
</div>
