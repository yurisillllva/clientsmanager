<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ChartRepository;
use Illuminate\Http\Response;

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
            return response()->json(['error' => 'Não autenticado'], Response::HTTP_UNAUTHORIZED);
        }

        $data = $this->repository->getStatesData($userId);

        if ($data->isEmpty()) {
            return response()->json(['error' => 'Nenhum dado encontrado'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'labels' => $data->pluck('state'),
            'data' => $data->pluck('total')
        ], Response::HTTP_OK);
    }

    public function cities()
    {
        $userId = auth()->id();

        if (!$userId) {
            return response()->json(['error' => 'Não autenticado'], Response::HTTP_UNAUTHORIZED);
        }

        $data = $this->repository->getCitiesData($userId);

        if ($data->isEmpty()) {
            return response()->json(['error' => 'Nenhum dado encontrado'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'labels' => $data->pluck('city'),
            'data' => $data->pluck('total')
        ], Response::HTTP_OK);
    }
}