<?php

namespace App\Services;

use App\Models\Instance;
use App\Models\Vault;

final class CreateVault extends ApiService
{
    public function __construct()
    {
        parent::__construct('post', "api/vaults");
    }

    public function store(array $data): ?Vault
    {
        $response = $this->call($data);

        if ($response === null || !isset($response['id'])) {
            return null;
        }

        return Vault::create($response->only([
            'id',
            'name',
        ])->all() + [
            'last_synced_at' => now(),
            'instance_id' => Instance::first()->id,
        ]);
    }
}
