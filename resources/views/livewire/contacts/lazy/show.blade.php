<?php

use App\Models\Contact;
use App\Models\Vault;
use App\Services\GetContact;

use function Livewire\Volt\mount;
use function Livewire\Volt\placeholder;
use function Livewire\Volt\state;

placeholder(<<<'HTML'
<div>
  <livewire:contacts.show :$vault :$contact />
</div>
HTML
);

state(['vault','contact']);

mount(function (Vault $vault, Contact $contact) {
    $this->vault = $vault;
    $this->contact = $contact;

    if ($contact->last_synced_at < now()->subMinutes(5)) {
        // fetch and store the contact
        $updateContact = (new GetContact($this->vault, $contact->id))->store();

        if ($updateContact !== null) {
           $this->contact = $updateContact;
        }
    }
});
?>

<div>
  <livewire:contacts.show :$vault :$contact />
</div>
