<?php

namespace App\Services;

use App\Models\Vault;

class SyncVaults
{
    public function __invoke()
    {
        $vaults = (new GetVaults())->call();

        if ($vaults === null) {
            return;
        }

        $localVaults = Vault::all();

        foreach ($vaults as $vaultData) {
            // test if vault exist in localVaults
            $vault = $localVaults->firstWhere('id', $vaultData['id']);

            if ($vault === null || $vault->last_synced_at < now()->subMinutes(5)) {
                // fetch and store the vault
                tap((new GetVault($vaultData['id']))
                    ->store(), fn (?Vault $newVault) =>
                    $newVault && $localVaults->push($newVault)
                );
            }
        }

        // delete local vaults that are not in remote vaults anymore
        $remoteIds = $vaults->pluck('id')->toArray();

        $localVaults->filter(fn ($vault) =>
            !in_array($vault->id, $remoteIds)
        )->each(function ($vault) use ($localVaults) {
            $localVaults = $localVaults->pull($vault);
            $vault->delete();
        });

        return $localVaults;
    }
}
