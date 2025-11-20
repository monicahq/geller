<?php

namespace App\Services;

use App\Models\User;

final class GetUser extends ApiService
{
    public function __construct()
    {
        parent::__construct('get', 'api/user');
    }

    public function store(): User
    {
        $response = $this->execute();

        $data = collect($response)
            ->only(['name', 'email'])
            ->toArray();

        return User::create($data);
    }
}
