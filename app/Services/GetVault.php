<?php

namespace App\Services;

use App\Models\Instance;
use App\Models\Vault;

final class GetVault extends ApiService
{
    public function __construct(protected string $vaultId)
    {
        parent::__construct('get', "api/vaults/{$vaultId}");
    }

    public function store(): ?Vault
    {
        $response = $this->call();

        if ($response === null || !isset($response['id']) || $response['id'] !== $this->vaultId) {
            return null;
        }

        return Vault::updateOrCreate([
            'id' => $response['id'],
        ], $response->only([
            'name',
        ])->all() + [
            'last_synced_at' => now(),
            'instance_id' => Instance::first()->id,
        ]);
    }
}
