<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
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
        $data = $this->repository->getStatesData(auth()->id());
        
        return response()->json([
            'labels' => $data->pluck('state'),
            'data' => $data->pluck('total')
        ]);
    }

    public function cities()
    {
        $data = $this->repository->getCitiesData(auth()->id());
        
        return response()->json([
            'labels' => $data->pluck('city'),
            'data' => $data->pluck('total')
        ]);
    }
}
