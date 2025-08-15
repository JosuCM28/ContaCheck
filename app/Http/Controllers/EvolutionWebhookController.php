<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class EvolutionWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $instance = config('services.evolution.instance');
        $base_url = config('services.evolution.base_url') . '/message/sendText/' . $instance;
        $api_key = config('services.evolution.api_key');

        $payload = $request->all();

        if (data_get($payload, 'data.key.fromMe') === true) {
            return response()->json(['status' => 'ignored_fromMe'], 200);
        }

        $sender = $payload['data']['key']['remoteJid'] ?? null;

        if ($sender) {
            $phoneNumberRaw = explode('@', $sender)[0];
            $phoneNumber = substr($phoneNumberRaw, -10);

            $client = Client::where('phone', $phoneNumber)->first();

            if ($client) {
                $clientName = Str::title($client->name);
                $counter = $client->counter;

                if ($counter) {
                    $counterName = Str::title($counter->full_name);
                    $counterNumber = $counter->phone;

                    try {
                        Http::timeout(15)
                            ->withHeaders([
                                'apikey' => $api_key,
                                'Accept' => 'application/json',
                            ])
                            ->post($base_url, [
                                'number' => "52" . $phoneNumber,
                                'text' => "📢 *Estimado {$clientName}*,\n\n" .
                                    "Para más información, por favor contacte a su contador asignado:\n\n" .
                                    "👨‍💼 *{$counterName}*\n" .
                                    "📞 *Número:* {$counterNumber}\n\n" .
                                    "🕒 *Horario de atención:*\n" .
                                    "Lunes a Viernes de 9:00 a.m. a 6:30 p.m.\n\n" .
                                    "✅ Gracias por su atención."
                            ])
                            ->throw()
                            ->json();
                    } catch (RequestException $e) {
                        report($e);
                    }
                } else {
                    try {
                        Http::timeout(15)
                            ->withHeaders([
                                'apikey' => $api_key,
                                'Accept' => 'application/json',
                            ])
                            ->post($base_url, [
                                'number' => "52" . $phoneNumber,
                                'text' => "📢 *Estimado(a)*,\n\n" .
                                    "Para más información, por favor contacte a el despacho contable Baltazar Montes:\n\n" .
                                    "📞 *Número:* 2263161354 / 2263160629 \n\n" .
                                    "🕒 *Horario de atención:*\n" .
                                    "Lunes a Viernes de 9:00 a.m. a 6:30 p.m.\n\n" .
                                    "✅ Gracias por su atención."
                            ])
                            ->throw()
                            ->json();
                    } catch (RequestException $e) {
                        report($e);
                    }
                }
            } else {
                try {
                    Http::timeout(15)
                        ->withHeaders([
                            'apikey' => $api_key,
                            'Accept' => 'application/json',
                        ])
                        ->post($base_url, [
                            'number' => "52" . $phoneNumber,
                            'text' => "📢 *Estimado(a)*,\n\n" .
                                "Para más información, por favor contacte a el despacho contable Baltazar Montes:\n\n" .
                                "📞 *Número:* 2263161354 / 2263160629 \n\n" .
                                "🕒 *Horario de atención:*\n" .
                                "Lunes a Viernes de 9:00 a.m. a 6:30 p.m.\n\n" .
                                "✅ Gracias por su atención."
                        ])
                        ->throw()
                        ->json();
                } catch (RequestException $e) {
                    report($e);
                }
            }
        }

        // Log::info('Received Evolution webhook data:', $payload);
        
        return response()->json(['status' => 'success'], 200);
    }
}
