<?php

namespace App\Services;

use App\Models\Instance;
use Illuminate\Support\Facades\Http;
use Native\Mobile\Facades\SecureStorage;

abstract class ApiService
{
    protected Instance $instance;

    public function __construct(
        public string $method,
        public string $uri
    )
    {
        $this->instance = Instance::firstOrFail();
    }

    public function execute(): array
    {
        $response = Http::withToken(SecureStorage::get('api_token'))
            ->{$this->method}($this->instance->url . $this->uri)
            ->throw();

        return $response->json()['data'];
    }
}
