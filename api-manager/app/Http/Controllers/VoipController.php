<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class VoipController extends Controller
{
    public function initiateCall(Client $client)
{
    $twilio = new Client(
        env('TWILIO_SID'),
        env('TWILIO_AUTH_TOKEN')
    );

    $twilio->calls->create(
        $client->phone, // Destino (número da saída da ligação)
        env('TWILIO_NUMBER'), // Origem (o responsável por discar)
        ["url" => "http://demo.twilio.com/docs/voice.xml"]
    );

    return response()->json(['message' => 'Chamada iniciada']);
}
}
