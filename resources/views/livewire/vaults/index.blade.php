<?php

use Illuminate\Support\Collection;

use function Livewire\Volt\mount;
use function Livewire\Volt\state;

state(['vaults']);

mount(function(Collection $vaults)  {
    $this->vaults = $vaults;
});
?>

<div>
  @foreach ($vaults as $vault)
    <div class="mb-4">
      <x-link href="{{ route('vault.show', $vault) }}">
          {{ $vault->name }}
      </x-link>
    </div>
  @endforeach
</div>
