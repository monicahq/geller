<?php

use App\Models\Vault;

use function Livewire\Volt\mount;
use function Livewire\Volt\state;

state(['vault']);

mount(function (Vault $vault) {
    $this->vault = $vault;
});
?>

<x-slot:vault :vault="$vault"></x-slot>

<x-slot:breadcrumbs :breadcrumbs="[
  ['url' => route('home')],
]"></x-slot>

<div>
    <h1 class="text-2xl font-bold mb-4">{{ $vault->name }}</h1>

    <livewire:contacts.index :$vault />
</div>
