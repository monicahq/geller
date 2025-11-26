<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Native\Mobile\Facades\SecureStorage;

final class TokenHelper
{
    public static function get(): ?string
    {
        if (empty($token = SecureStorage::get('api_token'))
            && App::environment('local')
            && !empty($localToken = config('auth.local_token'))) {
                Log::info('Using local token for authentication.');
                return $localToken;
        }

        return $token;
    }
}
