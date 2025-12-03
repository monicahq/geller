<?php

namespace App\Services;

use App\Models\Contact;

class SyncContact
{
    public function __construct(public Contact $contact)
    { }

    public function __invoke(): Contact
    {
        if ($this->contact->last_synced_at < now()->subMinutes(5)) {
            // fetch and store the contact
            $contact = (new GetContact($this->contact->vault, $this->contact->id))->store();

            if ($contact !== null) {
                return $contact;
            }
        }

        return $this->contact;
    }
}
