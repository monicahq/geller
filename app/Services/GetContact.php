<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\Vault;

final class GetContact extends ApiService
{
    public function __construct(public Vault $vault, protected string $contactId)
    {
        parent::__construct('get', "api/vaults/{$vault->id}/contacts/{$contactId}");
    }

    public function store(): ?Contact
    {
        $response = $this->call();

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
