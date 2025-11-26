<?php

use App\Models\Contact;
use App\Models\Vault;

use function Livewire\Volt\mount;
use function Livewire\Volt\state;

state(['vault','contact']);

mount(function (Vault $vault, Contact $contact) {
  $this->vault = $vault;
  $this->contact = $contact;
});
?>

<div>
  <x-link href="{{ route('vault.show', $vault) }}">
    <div class="flex items-center gap-2">
      <x-phosphor-caret-left class="size-4 min-w-3" />
      <span>
        {{ __('Vault') }}
      </span>
    </div>
  </x-link>

  <h1 class="text-2xl font-bold mb-4">
    {{ $contact->name }}
  </h1>
</div>
