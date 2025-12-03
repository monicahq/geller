@props([
  'vault' => null,
  'breadcrumbs' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <x-layouts.meta />
  </head>
  <body class="nativephp-safe-area font-sans text-gray-900 dark:text-white antialiased">

    <x-layouts.app.header :vault="$vault != null ? $vault->attributes->get('vault') : null" :breadcrumbs="$breadcrumbs != null ? $breadcrumbs->attributes->get('breadcrumbs') : null" />

    <main class="flex flex-1 flex-col min-h-screen bg-gray-50 sm:justify-center sm:pt-0 px-2 py-px dark:bg-[#151B23]">
      <div class="mx-auto flex w-full grow flex-col items-stretch rounded-lg bg-[#F9FBFC] shadow-xs ring-1 ring-[#E6E7E9] dark:bg-[#202830] dark:ring-gray-700">
        {{ $slot }}
      </div>
    </main>

    <x-layouts.app.footer />

    @fluxScripts
    @livewireScripts
    @livewireScriptConfig
  </body>
</html>
