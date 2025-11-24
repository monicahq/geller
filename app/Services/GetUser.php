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
        $data = $this->call()->toArray();

        if ($user = User::first()) {
            return $user->update($data);
        }
        
        return User::create($data);
    }
}
