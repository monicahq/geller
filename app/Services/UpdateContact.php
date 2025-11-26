<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\Vault;

final class UpdateContact extends ApiService
{
    public function __construct(public Vault $vault, protected string $contactId)
    {
        parent::__construct('put', "api/vaults/{$vault->id}/contacts/{$contactId}");
    }

    public function store(array $data): ?Contact
    {
        $response = $this->call($data);

        if ($response === null || !isset($response['id']) || $response['id'] !== $this->contactId) {
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
