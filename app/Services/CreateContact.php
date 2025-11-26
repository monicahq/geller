<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\Vault;

final class CreateContact extends ApiService
{
    public function __construct(public Vault $vault)
    {
        parent::__construct('post', "api/vaults/{$vault->id}/contacts");
    }

    public function store(array $data): ?Contact
    {
        $response = $this->call($data);

        if ($response === null || !isset($response['id'])) {
            return null;
        }

        return $this->vault->contacts()->updateOrCreate([
            'id' => $response['id'],
        ], $response->only([
            'name',
        ])->all() + [
            'last_synced_at' => now(),
        ]);
    }
}
