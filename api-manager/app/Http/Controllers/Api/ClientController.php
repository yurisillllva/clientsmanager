<?php

namespace App\Http\Controllers\Api;

use App\Repositories\ClientRepository;
use App\Http\Requests\StoreClientRequest;

class ClientController extends Controller
{
    public function index(ClientRepository $repository)
    {
        return $repository->paginate(auth()->id());
    }

    public function store(StoreClientRequest $request, ClientRepository $repository)
    {
        $client = $repository->create(auth()->id(), $request->validated());
        return response()->json($client, 201);
    }
}
