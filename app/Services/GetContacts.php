<?php

namespace App\Services;

use App\Models\Vault;

final class GetContacts extends ApiService
{
    public function __construct(Vault $vault)
    {
        parent::__construct('get', "api/vaults/{$vault->external_id}/contacts");
    }
}
