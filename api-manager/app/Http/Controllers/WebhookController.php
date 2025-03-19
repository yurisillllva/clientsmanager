<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handleClient(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'sometimes',
            'city' => 'sometimes',
            'state' => 'sometimes|size:2',
            'photo' => 'sometimes|url',
            'age' => 'sometimes|integer',
        ]);

        $client = Client::create($validated);
        return response()->json($client);
    }
}
