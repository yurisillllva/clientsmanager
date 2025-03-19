<?php 

namespace App\Repositories;

use App\Models\Client;

class ClientRepository
{
    public function paginate($userId, $perPage = 5, $search = null)
    {
        return Client::query()
        ->where('user_id', $userId)
        ->with(['user' => function ($query) { // carregando os relacionamentos de modelos de forma antecipada com o ORM do Laravel
            $query->select('id', 'name');
        }])
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        })
        ->orderBy('created_at', 'desc')
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