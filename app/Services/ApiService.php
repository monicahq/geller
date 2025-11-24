<?php

namespace App\Services;

use App\Models\Instance;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;

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

    public function call(): Collection
    {
        $response = Http::withToken(request()->attributes->get('token'));

        if (App::environment('local')) {
            $response = $response->withoutVerifying();
        }

        $response = $response
            ->{$this->method}($this->instance->url . $this->uri)
            ->throw();

        return collect($response->json()['data']);
    }
}
