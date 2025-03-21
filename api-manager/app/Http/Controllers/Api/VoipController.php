<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Twilio\Rest\Client as TwilioClient;
use Illuminate\Http\Request;

class VoipController extends Controller
{
    public function initiateCall(Client $client)
    {
        if (!$client->phone) {
            return response()->json([
                'error' => 'O cliente não possui um número de telefone cadastrado.'
            ], 400);
        }
    
        // Formata o número de telefone (adiciona o código do país, se necessário)
        $phone = $this->formatPhoneNumber($client->phone);
    
        $twilio = new TwilioClient(
            config('services.twilio.sid'),
            config('services.twilio.auth_token')
        );
    
        try {
            $call = $twilio->calls->create(
                $phone, // Número de telefone formatado
                config('services.twilio.from'), // Número Twilio
                ["url" => "http://demo.twilio.com/docs/voice.xml"] // URL do TwiML
            );
    
            return response()->json([
                'message' => 'Chamada VOIP iniciada!',
                'sid' => $call->sid
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Falha na ligação: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Formata o número de telefone para incluir o código do país (+55).
     */
    private function formatPhoneNumber($phone)
    {
        // Remove todos os caracteres não numéricos
        $phone = preg_replace('/[^0-9]/', '', $phone);
    
        // Adiciona o código do país (+55) se o número não começar com +
        if (!str_starts_with($phone, '+')) {
            $phone = '+55' . $phone;
        }
    
        return $phone;
    }
}
