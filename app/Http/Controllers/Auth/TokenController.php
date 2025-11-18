<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Instance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use RuntimeException;
use Native\Mobile\Facades\SecureStorage;

final class TokenController extends Controller
{
    public function __invoke(Request $request)
    {
        $state = $request->session()->pull('state');
        $codeVerifier = $request->session()->pull('code_verifier');

        throw_unless(
            is_string($state)
            && strlen($state) > 0
            && hash_equals($state, (string) $request->state),
            InvalidArgumentException::class
        );

        throw_unless(
            is_string($codeVerifier) && strlen($codeVerifier) > 0,
            InvalidArgumentException::class
        );

        $instance = Instance::firstOrFail();

        [$api_token, $expiresAt] = $this->getToken($request, $instance, $codeVerifier);

        SecureStorage::set('api_token', $api_token);
        SecureStorage::set('expires_at', optional($expiresAt)->timestamp);

        return redirect()->route('home');
    }

    private function getToken(Request $request, Instance $instance, string $codeVerifier): array
    {
        $api_token = $expiresAt = null;

        $response = Http::withHeader('User-Agent', $request->header('User-Agent'))
            ->post($instance->url . 'auth/token', [
                'code_verifier' => $codeVerifier,
                'code' => $request->code,
            ])
            ->throw();

        $payload = $response->json();

        if (! is_array($payload)) {
            throw new RuntimeException('Token endpoint returned an unexpected payload.');
        }

        if (isset($payload['access_token']) && is_string($payload['access_token']) && $payload['access_token'] !== '') {
            $api_token = $payload['access_token'];
        }

        if (isset($payload['expires_in']) && is_numeric($payload['expires_in'])) {
            $expiresAt = now()->addSeconds($payload['expires_in']);
        }

        return [$api_token, $expiresAt];
    }
}
