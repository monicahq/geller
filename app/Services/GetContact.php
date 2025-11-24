<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\Vault;

final class GetContact extends ApiService
{
    public function __construct(public Vault $vault, string $contactId)
    {
        parent::__construct('get', "api/vaults/{$vault->external_id}/contacts/{$contactId}");
    }

    public function store(): Contact
    {
        $data = $this->call();

        $externalId = $data['id'];
        $values = $data->except('id')->merge([
            'last_synced_at' => now(),
        ])->all();

        return $this->vault->contacts()->updateOrCreate([
            'external_id' => $externalId,
        ], $values);
    }
}
