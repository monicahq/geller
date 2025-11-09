<?php

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\Instance;

new #[Layout('components.layouts.guest')] class extends Component
{

    public string $url = '';

    public function mount()
    {
        $this->url = Instance::first()->url;
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(Request $request)
    {
        $request->session()->put('state', $state = Str::random(40));

        $request->session()->put(
            'code_verifier', $codeVerifier = Str::random(128)
        );

        $codeChallenge = strtr(rtrim(
            base64_encode(hash('sha256', $codeVerifier, true))
        , '='), '+/', '-_');

        $query = http_build_query([
            'redirect_uri' => 'http://geller.test/auth/callback',
            'state' => $state,
            'code_challenge' => $codeChallenge,
        ]);

        return redirect('http://rachel.test/auth/authorize?'.$query);
    }
}; ?>

<div class="grid min-h-screen w-screen grid-cols-1 lg:grid-cols-2">
  <div class="mx-auto flex w-full max-w-2xl flex-1 flex-col justify-center gap-y-10 px-5 py-10 sm:px-30">
    <!-- Title -->
    <div class="flex flex-col items-center gap-x-2">
      <div class="h-30 w-30 mb-10">
        <x-app-logo-icon />
      </div>
      <h1 class="text-2xl font-normal text-gray-900 dark:text-neutral-200 text-center">
        {{ __('To start using Monica, please login to your account.') }}
      </h1>
    </div>

    <!-- login form -->
    <x-box>
      <form method="POST" wire:submit.prevent="login" class="flex flex-col space-y-4">
        <!-- Instance information -->
        <div class="w-full border border-gray-200 rounded-lg px-2 py-1 flex items-center justify-between bg-gray-50">
          <span class="text-xs text-gray-500 font-mono">
            {{ $this->url }}
          </span>
          <x-phosphor-pencil-simple class="w-4 h-4 text-gray-500" />
        </div>

        <!-- URL to change the instance -->
        <x-input wire:model="url" id="url" :label="__('URL')" type="text" required autofocus autocomplete="url" :placeholder="__('https://example.com')" />

        <div class="flex w-full">
          <flux:button  icon="shield-check" variant="primary" type="submit" data-test="login-button" class="w-full">
            {{ __('Log in') }}
          </flux:button>
        </div>
      </form>
    </x-box>

    <ul class="text-xs text-gray-600 text-center">
      <li>&copy; {{ config('app.name') }} {{ now()->format('Y') }}</li>
    </ul>
  </div>
</div>
