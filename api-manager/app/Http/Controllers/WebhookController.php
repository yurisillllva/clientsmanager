<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WebhookController extends Controller
{
    public function handleClient(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:clients',
                'phone' => 'required|string|max:20',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string',
                'state' => 'nullable|string|size:2',
                'photo' => 'nullable|url',
                'age' => 'nullable|integer|min:1'
            ]);

            $validated['user_id'] = 1;

            $client = Client::create($validated);

            return response()->json(['response' => true], 201); 
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Erro de validação',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro interno no servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
