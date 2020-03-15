<?php

namespace App\Services;

use App\Models\Client;

class ClientService
{
    private $repository;

    public function __construct(Client $client)
    {
        $this->repository = $client;
    }

    public function createCustomer()
    {

    }
}
