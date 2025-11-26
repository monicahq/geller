<?php

use App\Services\SyncVaults;

use function Livewire\Volt\mount;
use function Livewire\Volt\placeholder;
use function Livewire\Volt\state;

placeholder(<<<'HTML'
<div>
  Loading...
  <livewire:vaults.index :vaults="App\Models\Vault::all()" />
</div>
HTML
);

state(['vaults']);

mount(function()  {
    $this->vaults = (new SyncVaults)();
});
?>

<div>
  <livewire:vaults.index :$vaults />
</div>
