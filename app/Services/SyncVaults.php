<?php

namespace App\Services;

use App\Models\Vault;

class SyncVaults
{
    public function __invoke()
    {
        $vaults = (new GetVaults())->call();

        $localVaults = Vault::all();

        foreach ($vaults as $vaultData) {
            // test if vault exist in localVaults
            $vault = $localVaults->firstWhere('external_id', $vaultData['id']);

            if ($vault === null || $vault->last_synced_at < now()->subMinutes(5)) {
                // fetch and store the vault
                (new GetVault($vaultData['id']))->store();
            }
        }

        // delete local vaults that are not in remote vaults anymore
        $remoteIds = $vaults->pluck('id')->toArray();
        
        $localVaults->filter(fn ($vault) =>
            !in_array($vault->external_id, $remoteIds)
        )->each(function ($vault) {
            $vault->delete();
        });

        return Vault::all();
    }
}
