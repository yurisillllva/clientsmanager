<?php 

namespace App\Repositories;

use App\Models\Client;

class ClientRepository
{
    public function paginate($userId, $perPage = 5)
    {
        return Client::where('user_id', $userId)
            ->paginate($perPage)
            ->withQueryString();
    }

    // Outros métodos CRUD...
}