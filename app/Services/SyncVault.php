<?php

namespace App\Services;

use App\Models\Vault;
use Illuminate\Database\Eloquent\Collection;

class SyncVault
{
    public function __construct(public Vault $vault)
    { }

    public function __invoke(): Vault
    {
        if ($this->vault->last_synced_at < now()->subMinutes(5)) {
            // fetch and store the vault
            $vault = (new GetVault($this->vault->id))->store();

            if ($vault !== null) {
                return $vault;
            }
        }

        return $this->vault;
    }
}
