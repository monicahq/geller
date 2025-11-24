<?php

namespace App\Services;

final class GetVaults extends ApiService
{
    public function __construct()
    {
        parent::__construct('get', 'api/vaults');
    }
}
