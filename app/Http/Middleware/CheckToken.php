<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Instance;
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
        if ($this->getToken() === null) {
            SecureStorage::delete('api_token');
            SecureStorage::delete('expires_at');
            Instance::all()->each(fn (Instance $instance) => $instance->delete());

            return redirect()->route('login');
        }

        return $next($request);
    }

    private function getToken(): ?string
    {
        if (empty($token = SecureStorage::get('api_token'))) {
            if (App::environment('local') && !empty($localToken = config('auth.local_token'))) {
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
}
