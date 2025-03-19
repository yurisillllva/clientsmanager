<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChartController;
use App\Http\Controllers\Api\VoipController;
use App\Http\Controllers\WebhookController;


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
    
    Route::get('charts/states', [ChartController::class, 'states']);
    Route::get('charts/cities', [ChartController::class, 'cities']);
    Route::post('clients/{client}/call', [VoipController::class, 'initiateCall']);
});

// Webhook
// Route::post('webhook/huggy-flow', [WebhookController::class, 'handleHuggyFlow'])
//     ->withoutMiddleware(['auth:api', 'throttle']);

Route::post('webhook/client', [WebhookController::class, 'handleClient'])
    ->middleware('verify.webhook');
