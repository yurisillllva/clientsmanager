<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientController;

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

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::apiResource('clients', ClientController::class);
    
    Route::post('clients/{client}/call', [VoipController::class, 'initiateCall']);
    Route::get('charts/states', [ChartController::class, 'states']);
    Route::get('charts/cities', [ChartController::class, 'cities']);
});

// Webhook
Route::post('webhook/client', [WebhookController::class, 'handleClient'])
    ->middleware('verify.webhook');
