<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Gift;
use App\Models\Instance;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
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
            return redirect('login');
        }

        $request->attributes->add(['token' => $token]);

        return $next($request);
    }
}
