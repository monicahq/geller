<?php

namespace App\Services;

use App\Models\Instance;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class ApiService
{
    protected Instance $instance;

    public function __construct(
        protected string $method,
        protected string $uri
    )
    {
        $instance = Instance::first();

        if ($instance === null) {
            $this->instance = Instance::create([
                'url' => config('app.app_instance_url'),
            ]);
        } else {
            $this->instance = $instance;
        }
    }

    public function call(mixed $data = null): ?Collection
    {
        try {
            $response = Http::withToken(request()->attributes->get('token'));

            if (App::environment('local')) {
                $response = $response->withoutVerifying();
            }

            $response = $response
                ->{$this->method}($this->instance->url . $this->uri, $data)
                ->throw();

            return collect($response->json()['data']);
        } catch (RequestException $e) {
            Log::error('API request failed: ' . $e->getMessage(), [
                'uri' => $this->uri,
                'method' => $this->method,
                'data' => $data,
                'status' => $e->response?->status(),
                'response' => $e->response?->body(),
            ]);
            
            return null;
        }
    }
}
