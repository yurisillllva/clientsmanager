<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Twilio\Rest\Client as TwilioClient;
use Illuminate\Http\Request;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;

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

        $token = new AccessToken(
            config('services.twilio.account_sid'),
            config('services.twilio.api_key'),
            config('services.twilio.api_secret'),
            3600,
            'web_client_' . uniqid()
        );

        $voiceGrant = new VoiceGrant();
        $voiceGrant->setOutgoingApplicationSid(config('services.twilio.twiml_app_sid'));

        
        $token->addGrant($voiceGrant);

        return response()->json([
            'token' => $token->toJwt(),
            'to' => $phone
        ]);

        
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
