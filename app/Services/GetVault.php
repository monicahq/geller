<?php

namespace App\Services;

use App\Models\Vault;

final class GetVault extends ApiService
{
    public function __construct(string $vaultId)
    {
        parent::__construct('get', "api/vaults/{$vaultId}");
    }

    public function store(): Vault
    {
        $data = $this->call();

        $externalId = $data['id'];
        $values = $data->except('id')->merge([
            'last_synced_at' => now(),
        ])->all();

        return Vault::updateOrCreate([
            'external_id' => $externalId,
        ], $values);
    }
}
