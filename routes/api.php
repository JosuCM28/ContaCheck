<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EvolutionWebhookController;


Route::post('webhooks/evolution', [EvolutionWebhookController::class, 'handle']);
