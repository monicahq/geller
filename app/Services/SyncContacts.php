<?php

namespace App\Services;

use App\Models\Vault;
use Illuminate\Database\Eloquent\Collection;

class SyncContacts
{
    public function __construct(public Vault $vault)
    { }

    public function __invoke(): Collection
    {
        $localContacts = $this->vault->contacts;
        $contacts = (new GetContacts($this->vault))->call();

        if ($contacts === null) {
            return $localContacts;
        }

        foreach ($contacts as $contactData) {
            // test if contact exist in localContacts
            $contact = $localContacts->firstWhere('id', $contactData['id']);

            if ($contact === null || $contact->last_synced_at < now()->subMinutes(5)) {
                // fetch and store the contact
                (new GetContact($this->vault, $contactData['id']))
                    ->store();
            }
        }

        // delete local contacts that are not in remote contacts
        $remoteContactIds = $contacts->pluck('id')->toArray();

        $localContacts->filter(fn ($contact) =>
            !in_array($contact->id, $remoteContactIds)
        )->each(function ($contact)  {
            $contact->delete();
        });

        return $this->vault->contacts;
    }
}
