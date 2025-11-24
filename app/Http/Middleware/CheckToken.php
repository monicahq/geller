<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Vault;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Native\Mobile\Facades\SecureStorage;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (($token = $this->getToken()) === null) {
            User::all()->each(fn (User $user) => $user->delete());
            Vault::all()->each(fn (Vault $vault) => $vault->delete());

            return redirect()->route('login');
        }

        return $this->next($request, $next, $token);
    }

    private function getToken(): ?string
    {
        if (empty($token = SecureStorage::get('api_token'))) {
            if (App::environment('local') && !empty($localToken = config('auth.local_token'))) {
                Log::info('Using local token for authentication.');
                return $localToken;
            }

            Log::warning('No API token found.');
            return null;;
        }

        $expiresAt = SecureStorage::get('expires_at');
        if (! empty($expiresAt) && now() > Carbon::createFromTimestamp($expiresAt)) {
            Log::warning(('API token has expired.'));
            return null;
        }

        return $token;
    }

    private function next(Request $request, Closure $next, string $token): Response
    {
        $request->attributes->add(['token' => $token]);

        return $next($request);
    }
}
