<?php

use App\Models\Vault;
use Illuminate\Support\Collection;

use function Livewire\Volt\mount;
use function Livewire\Volt\state;

state(['vault','contacts']);

mount(function (Vault $vault, Collection $contacts) {
    $this->vault = $vault;
    $this->contacts = $contacts;
});
?>

<div>
    @foreach ($contacts as $contact)
      <div class="mb-2">
        <x-link href="{{ route('contact.show', [$vault, $contact]) }}">
          {{ $contact->name }}
        </x-link>
      </div>
    @endforeach
</div>
