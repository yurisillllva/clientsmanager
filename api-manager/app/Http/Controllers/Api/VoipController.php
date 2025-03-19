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
        $twilio = new TwilioClient(
            config('services.twilio.sid'),
            config('services.twilio.auth_token')
        );

        try {
            $call = $twilio->calls->create(
                $client->phone,
                config('services.twilio.from'),
                ["url" => "http://demo.twilio.com/docs/voice.xml"]
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
}
