<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ClientRepository;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    public function index(ClientRepository $repository)
    {
        try {
            $user = auth('api')->user();
            if (!$user) {
                return response()->json(['error' => 'Não autenticado'], 401);
            }
            $clients = $repository->paginate();
            return ClientResource::collection($clients);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro interno'], 500);
        }
    }

    public function store(StoreClientRequest $request, ClientRepository $repository)
    {
        try {
            $validatedData = $request->validated();

            $client = $repository->create(auth()->id(), $validatedData);

            return (new ClientResource($client))
                ->response()
                ->setStatusCode(201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Erro de validação',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro interno',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(ClientRepository $repository, $id)
    {
        $client = $repository->findOrFail(auth()->id(), $id);
        return response()->json(new ClientResource($client), 200);
    }

    public function update(UpdateClientRequest $request, ClientRepository $repository, $id)
    {
        $client = $repository->update(auth()->id(), $id, $request->validated());
        return response()->json(new ClientResource($client), 200);
    }

    public function destroy(ClientRepository $repository, $id)
    {
        $repository->delete(auth()->id(), $id);
        return response()->json(null, 204);
    }
}
