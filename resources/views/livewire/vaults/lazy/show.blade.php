<?php

use App\Models\Vault;
use App\Services\GetVault;
use App\Services\SyncContacts;

use function Livewire\Volt\mount;
use function Livewire\Volt\placeholder;
use function Livewire\Volt\state;

placeholder(<<<'HTML'
<div>
  <livewire:vaults.show :$vault />
</div>
HTML
);

state(['vault','contacts']);

mount(function (Vault $vault) {
    $this->vault = $vault;
    if ($vault->last_synced_at < now()->subMinutes(5)) {
        // fetch and store the vault
        $updateVault = (new GetVault($vault->id))->store();

        if ($updateVault !== null) {
            $this->vault = $updateVault;
        }
    }
});
?>

<div>
  <livewire:vaults.show :$vault />
</div>
