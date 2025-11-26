<?php

use App\Models\User;
use App\Services\GetUser;

use function Livewire\Volt\mount;
use function Livewire\Volt\state;

state(['user']);

mount(function()  {
    $this->user = User::first() ?? (new GetUser())->store();
});
?>

<div>
  <h1 class="text-2xl font-bold mb-4">
    {{ __('Welcome, :name!', ['name' => $user->name]) }}
  </h1>

  <livewire:vaults.index />
</div>
