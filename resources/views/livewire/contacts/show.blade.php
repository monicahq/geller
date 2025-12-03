<?php

use App\Models\Contact;
use App\Models\Vault;

use function Livewire\Volt\mount;
use function Livewire\Volt\state;

state(['vault','contact'])->locked();

mount(function (Vault $vault, Contact $contact) {
  $this->vault = $vault;
  $this->contact = $contact;
});
?>

<x-slot:vault :vault="$vault"></x-slot>

<x-slot:breadcrumbs :breadcrumbs="[
  ['title' => $vault->name, 'url' => route('vaults.show', $vault)],
]"></x-slot>

<x-slot:title>
  {{ __('Contact Details') }} - {{ $contact->name }}
</x-slot>

<div>
  <livewire:contacts.lazy.show :$vault :$contact lazy />
</div>
