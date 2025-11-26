<?php

namespace App\Services;

use App\Http\Helpers\TokenHelper;
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
            $response = Http::withToken(TokenHelper::get());

            if (App::environment('local')) {
                $response = $response->withoutVerifying();
            }

            $response = $response
                ->asJson()
                ->{$this->method}($this->instance->url . $this->uri, $data)
                ->throw();

            $json = $response->json();

            if (!isset($json['data'])) {
                Log::warning('API response does not contain data key', [
                    'uri' => $this->uri,
                    'method' => $this->method,
                    'data' => $data,
                    'response' => $json,
                ]);

                return null;
            }

            return collect($json['data']);
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
