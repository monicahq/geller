<?php

use App\Models\Vault;
use App\Services\GetVault;
use App\Services\SyncContacts;

use function Livewire\Volt\mount;
use function Livewire\Volt\state;

state(['vault','contacts']);

mount(function (Vault $vault) {
    $this->vault = $vault;
    if ($vault->last_synced_at < now()->subMinutes(5)) {
        // fetch and store the vault
        $this->vault = (new GetVault($vault->external_id))->store();
    }

    $this->contacts = (new SyncContacts($vault))();
});
?>

<div>
    <h1 class="text-2xl font-bold mb-4">{{ $vault->name }}</h1>

    @foreach ($contacts as $contact)
      <div class="mb-2">
        <x-link href="{{ route('contact.show', [$vault, $contact]) }}">
          {{ $contact->name }}
        </x-link>
      </div>
    @endforeach

    <x-link href="{{ route('home') }}">
      Back
    </x-link>
</div>
