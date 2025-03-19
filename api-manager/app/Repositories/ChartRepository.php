<? 

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Support\Facades\DB;

class ChartRepository
{
    public function getStatesData($userId)
    {
        return Client::where('user_id', $userId)
            ->select('state', DB::raw('count(*) as total'))
            ->groupBy('state')
            ->orderBy('state')
            ->get()
            ->map(function ($item) {
                return [
                    'state' => $item->state ?? 'Não informado',
                    'total' => $item->total
                ];
            });
    }

    public function getCitiesData($userId)
    {
        return Client::where('user_id', $userId)
            ->select('city', DB::raw('count(*) as total'))
            ->groupBy('city')
            ->orderBy('total', 'desc')
            ->take(20) // Coloquei o limite de 20 de cidades mais relevantes p/ teste
            ->get()
            ->map(function ($item) {
                return [
                    'city' => $item->city ?? 'Não informado',
                    'total' => $item->total
                ];
            });
    }
}