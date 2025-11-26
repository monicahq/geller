<?php

namespace App\Services;

use App\Models\Vault;

final class DestroyContact extends ApiService
{
    public function __construct(public Vault $vault, string $contactId)
    {
        parent::__construct('delete', "api/vaults/{$vault->id}/contacts/{$contactId}");
    }
}
