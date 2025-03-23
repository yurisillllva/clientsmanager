<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ChartRepository;

class ChartController extends Controller
{
    protected $repository;

    public function __construct(ChartRepository $repository)
    {
        $this->repository = $repository;
    }

    public function states()
    {
        $userId = auth()->id();

        if (!$userId) {
            return response()->json(['error' => 'Não autenticado'], 401); 
        }

        $data = $this->repository->getStatesData($userId);

        if ($data->isEmpty()) {
            return response()->json(['error' => 'Nenhum dado encontrado'], 404); 
        }

        return response()->json([
            'labels' => $data->pluck('state'),
            'data' => $data->pluck('total')
        ], 200); 
    }

    public function cities()
    {
        $userId = auth()->id();

        if (!$userId) {
            return response()->json(['error' => 'Não autenticado'], 401); 
        }

        $data = $this->repository->getCitiesData($userId);

        if ($data->isEmpty()) {
            return response()->json(['error' => 'Nenhum dado encontrado'], 404); 
        }

        return response()->json([
            'labels' => $data->pluck('city'),
            'data' => $data->pluck('total')
        ], 200); 
    }
}