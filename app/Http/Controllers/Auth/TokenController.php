<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Instance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use RuntimeException;

final class TokenController extends Controller
{
    public function create(Request $request)
    {
        $state = $request->session()->pull('state');
        $codeVerifier = $request->session()->pull('code_verifier');

        abort_unless(
            is_string($state)
            && strlen($state) > 0
            && hash_equals($state, (string) $request->state),
            InvalidArgumentException::class
        );

        abort_unless(
            is_string($codeVerifier) && strlen($codeVerifier) > 0,
            InvalidArgumentException::class
        );

        $instance = Instance::query()->first();

        if ($instance === null) {
            throw new RuntimeException('No instance configured.');
        }

        $response = Http::asForm()
            ->withHeader('User-Agent', $request->header('User-Agent'))
            ->post(rtrim($instance->url, '/') . '/auth/token', [
                'code_verifier' => $codeVerifier,
                'code' => $request->code,
            ])
            ->throw();

        $payload = $response->json();

        if (! is_array($payload)) {
            throw new RuntimeException('Token endpoint returned an unexpected payload.');
        }

        $updates = [];

        if (isset($payload['access_token']) && is_string($payload['access_token']) && $payload['access_token'] !== '') {
            $updates['access_token'] = $payload['access_token'];
        }

        if (isset($payload['token_type']) && is_string($payload['token_type']) && $payload['token_type'] !== '') {
            $updates['token_type'] = $payload['token_type'];
        }

        if (isset($payload['expires_in']) && is_numeric($payload['expires_in'])) {
            $expiresIn = (int) $payload['expires_in'];

            if ($expiresIn > 0) {
                $updates['expires_in'] = $expiresIn;
            }
        }

        if ($updates !== []) {
            $instance->forceFill($updates)->save();
        }

        return new JsonResponse($payload, $response->status());
    }
}
