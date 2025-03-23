<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChartController;
use App\Http\Controllers\Api\VoipController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Log;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:api')->group(function () {
    Route::apiResource('clients', ClientController::class);
    
    Route::get('charts/states', [ChartController::class, 'states'])->name('charts.states');
    Route::get('charts/cities', [ChartController::class, 'cities'])->name('charts.cities');
    Route::post('clients/{client}/call', [VoipController::class, 'initiateCall']);
});

Route::post('webhook/client', [WebhookController::class, 'handleClient']);

//Rota pública para TwilioApp
Route::post('/twiml', function (Request $request) {
    $to = $request->input('To'); 
    Log::debug('Valor da variável:', ['variavel' => $request->input('To')]);
    return response(
        '<Response>
            <Dial callerId="'.config('services.twilio.caller_id').'">
                <Number>'.$to.'</Number>
            </Dial>
        </Response>',
        200
    )->header('Content-Type', 'text/xml');
})->name('generate.twiml');
