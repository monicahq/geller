<?php

use App\Models\Contact;
use App\Models\Vault;
use App\Services\SyncContact;

use function Livewire\Volt\mount;
use function Livewire\Volt\placeholder;
use function Livewire\Volt\state;

placeholder(<<<'HTML'
<div>
  <livewire:contacts.partials.show :$vault :$contact />
</div>
HTML
);

state(['vault','contact']);

mount(function (Vault $vault, Contact $contact) {
  $this->vault = $vault;
  $this->contact = (new SyncContact($contact))();
});
?>

<div>
  <livewire:contacts.partials.show :$vault :$contact />
</div>
