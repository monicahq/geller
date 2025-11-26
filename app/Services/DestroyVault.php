<?php

namespace App\Services;

final class DestroyVault extends ApiService
{
    public function __construct(string $vaultId)
    {
        parent::__construct('delete', "api/vaults/{$vaultId}");
    }
}
