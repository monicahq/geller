<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Gift;
use App\Models\Instance;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = Instance::query()->first()->access_token;

        if (empty($token)) {
            return redirect('login');
        }

        $request->attributes->add(['token' => $token]);

        return $next($request);
    }
}
