<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\Vault;

class SyncContacts
{
    public function __construct(public Vault $vault)
    { }

    public function __invoke()
    {
        $contacts = (new GetContacts($this->vault))->call();

        if ($contacts === null) {
            return;
        }

        $localContacts = $this->vault->contacts;

        foreach ($contacts as $contactData) {
            // test if contact exist in localContacts
            $contact = $localContacts->firstWhere('id', $contactData['id']);

            if ($contact === null || $contact->last_synced_at < now()->subMinutes(5)) {
                // fetch and store the contact
                tap((new GetContact($this->vault, $contactData['id']))
                    ->store(), fn (?Contact $newContact) =>
                    $newContact && $localContacts->push($newContact)
                );
            }
        }

        // delete local contacts that are not in remote contacts
        $remoteContactIds = $contacts->pluck('id')->toArray();

        $localContacts->filter(fn ($contact) =>
            !in_array($contact->id, $remoteContactIds)
        )->each(function ($contact) use ($localContacts) {
            $localContacts = $localContacts->pull($contact);
            $contact->delete();
        });

        return $localContacts;
    }
}
