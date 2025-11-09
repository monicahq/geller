<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuthCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

final class TokenController extends Controller
{
    public function create(Request $request)
    {
        $state = $request->session()->pull('state');

        $codeVerifier = $request->session()->pull('code_verifier');

        abort_unless(
            strlen($state) > 0 && $state === $request->state,
            InvalidArgumentException::class
        );

        $response = Http::asForm()
            ->withHeader('User-Agent', $request->header('User-Agent'))
            ->post('http://rachel.test/auth/token', [
                'code_verifier' => $codeVerifier,
                'code' => $request->code,
            ])
            ->throw();

        return $response;
    }
}
