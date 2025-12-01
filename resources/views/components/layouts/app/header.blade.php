@props([
  'vault' => null,
])

<header class="top-0 left-0 w-full pl-[var(--inset-left)] pr-[var(--inset-right)]" x-data="{
  settings: false,
  vault: false,
}">


  <search class="flex-1 items-center gap-3 pt-3 pb-3" aria-label="Main navigation">
    <!-- search -->
    <flux:input type="text" name="search" placeholder="{{ __('Search...') }}" class="w-full rounded-md border-0 px-3 py-2 text-sm placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-blue-500 dark:focus:bg-[#2A3745]">
      <x-slot name="iconLeading">
        <flux:button size="sm" variant="subtle" x-on:click="settings = true" icon="bars-3" class="ms-1" />
      </x-slot>

      @if ($vault)
      <x-slot name="iconTrailing">
        <flux:button size="sm" variant="subtle" x-on:click="vault = true" icon="cube" class="me-1" />
      </x-slot>
      @endif
    </flux:input>
  </search>

  <!-- settings overlay -->
  <div x-cloak x-show="settings" x-transition:enter="transition duration-50 ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition duration-50 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 bg-white dark:bg-gray-900">
    <div class="flex h-full flex-col">
      <!-- menu header -->
      <div class="flex items-center justify-between border-b border-gray-200 px-2 py-1 dark:border-gray-700">
        <button x-on:click="settings = false" class="flex items-center gap-2 rounded-md border border-transparent py-2 font-medium hover:border-gray-200 hover:bg-gray-100 dark:hover:border-gray-600 dark:hover:bg-gray-800">
          <x-phosphor-x class="size-5 text-gray-600 dark:text-gray-400" />
        </button>
      </div>

      <!-- menu content -->
      <div class="flex-1 space-y-4 p-4">
        <x-link x-on:click="settings = false" href="" class="flex items-center gap-3 rounded-md p-3 text-lg font-medium text-gray-800 transition-colors hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
          <x-phosphor-user class="size-5 text-gray-600 dark:text-gray-400" />
          {{ __('Profile') }}
        </x-link>
      </div>
    </div>
  </div>

  @if ($vault)
  <!-- vault overlay -->
  <div x-cloak x-show="vault" x-transition:enter="transition duration-50 ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition duration-50 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 bg-white dark:bg-gray-900">
    <div class="flex h-full flex-col">
      <!-- menu header -->
      <div class="flex items-center justify-between border-b border-gray-200 px-2 py-1 dark:border-gray-700">
        <button x-on:click="vault = false" class="flex items-center gap-2 rounded-md border border-transparent py-2 font-medium hover:border-gray-200 hover:bg-gray-100 dark:hover:border-gray-600 dark:hover:bg-gray-800">
          <x-phosphor-x class="size-5 text-gray-600 dark:text-gray-400" />
        </button>
      </div>

      <!-- menu content -->
      <div class="flex-1 space-y-4 p-4">
        <x-link href="{{ route('vaults.show', $vault) }}" class="flex items-center gap-3 rounded-md p-3 text-lg font-medium text-gray-800 transition-colors hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
          {{ $vault->name }}
        </x-link>

        <x-link href="{{ route('home') }}" class="flex items-center gap-3 rounded-md p-3 text-lg font-medium text-gray-800 transition-colors hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
          {{ __('Select another vault') }}
        </x-link>
      </div>
    </div>
  </div>
  @endif
</header>
