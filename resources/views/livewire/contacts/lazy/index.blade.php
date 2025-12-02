<?php

use App\Models\Vault;
use App\Services\SyncContacts;

use function Livewire\Volt\mount;
use function Livewire\Volt\placeholder;
use function Livewire\Volt\state;

placeholder(<<<'HTML'
<div>
  Loading...
  <livewire:contacts.partials.index :$vault :contacts="$vault->contacts" />
</div>
HTML
);

state(['vault','contacts']);

mount(function (Vault $vault) {
  $this->vault = $vault;
  $this->contacts = (new SyncContacts($this->vault))();
});
?>

<div>
  <livewire:contacts.partials.index :$vault :$contacts />
</div>
