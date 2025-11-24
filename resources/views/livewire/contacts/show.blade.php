<?php

use App\Models\Contact;
use App\Models\Vault;
use App\Services\GetContact;

use function Livewire\Volt\mount;
use function Livewire\Volt\state;

state(['vault','contact']);

mount(function (Vault $vault, Contact $contact) {
    $this->vault = $vault;
    $this->contact = $contact;

    if ($contact->last_synced_at < now()->subMinutes(5)) {
        // fetch and store the contact
        $this->contact = (new GetContact($this->vault, $contact->external_id))->store();
    }
});
?>

<div>
    <h1 class="text-2xl font-bold mb-4">{{ $contact->name }}</h1>

    <x-link href="{{ route('vault.show', $vault) }}">
      Back
    </x-link>
</div>
