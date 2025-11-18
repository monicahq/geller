<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
        $token = SecureStorage::get('api_token');

        if (empty($token)) {
            return redirect()->route('login');
        }

        $expiresAt = SecureStorage::get('expires_at');
        if (! empty($expiresAt) && now() > Carbon::createFromTimestamp($expiresAt)) {
            Log::warning(('API token has expired.'));
            return redirect()->route('login');
        }

        Log::info(('token present:'. $token));

        $request->attributes->add(['token' => $token]);

        return $next($request);
    }
}
