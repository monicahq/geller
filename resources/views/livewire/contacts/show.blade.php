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

<div>
  <livewire:contacts.lazy.show :$vault :$contact lazy />
</div>
