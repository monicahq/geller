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
  <h1 class="text-2xl font-bold mb-4">
    {{ $contact->name }}
  </h1>
</div>
