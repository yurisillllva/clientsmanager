<?php 

namespace App\Repositories;

use App\Models\Client;

class ClientRepository
{
    public function paginate($userId, $perPage = 5, $search = null)
    {
        return Client::where('user_id', $userId)
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
                });
            })
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findOrFail($userId, $clientId)
    {
        return Client::where('user_id', $userId)->findOrFail($clientId);
    }

    public function create($userId, array $data)
    {
        return Client::create(array_merge($data, ['user_id' => $userId]));
    }

    public function update($userId, $clientId, array $data)
    {
        $client = $this->findOrFail($userId, $clientId);
        $client->update($data);
        return $client;
    }

    public function delete($userId, $clientId)
    {
        $client = $this->findOrFail($userId, $clientId);
        return $client->delete();
    }
}