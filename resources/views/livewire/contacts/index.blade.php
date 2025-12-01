<?php

use App\Models\Vault;
use App\Services\CreateContact;

use function Livewire\Volt\mount;
use function Livewire\Volt\state;
use function Livewire\Volt\with;

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

  <div
    x-data="{
    showForm: false,
    toggle: function() { this.showForm = !this.showForm }
  }">
    <div class="fixed right-6 bottom-2 pl-[var(--inset-left)] pr-[var(--inset-right)] pb-[var(--inset-bottom)]" x-show="!showForm" x-transition:enter.duration.200ms>
      <flux:button icon="plus-circle" variant="subtle" @click.prevent="toggle()" icon:class="size-16 text-sky-300 dark:text-sky-700" />
    </div>

    <!-- create contact form -->
    <form method="POST" wire:submit="create" wire:cloak x-show="showForm"  x-transition:enter="transition duration-50 ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition duration-50 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 space-y-6 bg-white dark:bg-gray-900 flex flex-col gap-2">
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
  </div>
</div>
