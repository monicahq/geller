<?php

use App\Models\Vault;
use App\Services\SyncVault;

use function Livewire\Volt\mount;
use function Livewire\Volt\placeholder;
use function Livewire\Volt\state;

placeholder(<<<'HTML'
<div>
  <livewire:vaults.partials.show :$vault />
</div>
HTML
);

state(['vault','contacts']);

mount(function (Vault $vault) {
  $this->vault = (new SyncVault($vault))();
});
?>

<div>
  <livewire:vaults.partials.show :$vault />
</div>
