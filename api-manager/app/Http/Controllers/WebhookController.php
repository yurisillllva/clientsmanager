<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handleClient(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients',
            'phone' => 'required|string|max:20',
            'city' => 'nullable|string',
            'state' => 'nullable|string|size:2',
            'photo' => 'nullable|url',
            'age' => 'nullable|integer|min:1'
        ]);

        $client = Client::create($validated);
        return response()->json($client, 201);
    }
}
