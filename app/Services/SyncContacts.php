<?php

namespace App\Services;

use App\Models\Vault;

class SyncContacts
{
    public function __construct(public Vault $vault)
    { }

    public function __invoke()
    {
        $contacts = (new GetContacts($this->vault))->call();

        $localContacts = $this->vault->contacts;

        foreach ($contacts as $contactData) {
            // test if contact exist in localContacts
            $contact = $localContacts->firstWhere('external_id', $contactData['id']);

            if ($contact === null || $contact->last_synced_at < now()->subMinutes(5)) {
                // fetch and store the contact
                (new GetContact($this->vault, $contactData['id']))->store();
            }
        }

        // delete local contacts that are not in remote contacts
        $remoteContactIds = $contacts->pluck('id')->toArray();

        $localContacts->filter(fn ($contact) =>
            !in_array($contact->external_id, $remoteContactIds)
        )->each(function ($contact) {
            $contact->delete();
        });

        return $this->vault->refresh()->contacts;
    }
}
