<?php

namespace App\Services;

use App\Models\User;

final class GetUser extends ApiService
{
    public function __construct()
    {
        parent::__construct('get', 'api/user');
    }

    public function store(): ?User
    {
        $data = $this->call();

        if ($data === null) {
            return null;
        }

        if ($user = User::first()) {
            return $user->update($data->all());
        }

        return User::create($data->all());
    }
}
