<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EvolutionWebhookController;


Route::post('/evolution-webhook', [EvolutionWebhookController::class, 'handle']);
