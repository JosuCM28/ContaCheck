<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Str;

class EvolutionWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $instance = config('services.evolution.instance');
        $base_url = config('services.evolution.base_url') . '/message/sendText/' . $instance;
        $api_key = config('services.evolution.api_key');

        // Process the webhook data
        $data = $request->all();

        // Opción 1: desde sender
        $sender = $data['sender'] ?? null;
        if ($sender) {
            $phoneNumberRaw = explode('@', $sender)[0];
            $phoneNumber = substr($phoneNumberRaw, -10);
            $client = \App\Models\Client::where('phone', $phoneNumber)->first();
            if ($client) {
                $counter = $client->counter;
                if ($counter) {
                    $counterName = Str::title($counter->full_name);
                    $counterNumber = $counter->number;

                    try {
                        $res = Http::timeout(15)
                            ->withHeaders([
                                'apikey' => $api_key,
                                'Accept' => 'application/json',
                            ])
                            ->post($base_url, [
                                'number' => "52".$phoneNumber,
                                'textMessage' => [
                                    'text' => "📢 *Estimado(a)*,\n\n" .
                                        "Para más información, por favor contacte a su contador asignado:\n" .
                                        "👨‍💼 *{$counterName}*\n" .
                                        "📞 *Número:* {$counterNumber}\n\n" .
                                        "🕒 *Horario de atención:*\n" .
                                        "📅 Lunes a Viernes de 9:00 a.m. a 6:30 p.m.\n\n" .
                                        "✅ Gracias por su atención."
                                ],
                            ])
                            ->throw()
                            ->json();

                        if (isset($res['error'])) {
                            
                        }

                    } catch (RequestException $e) {
                        report($e);
                        
                    }
                }


            }
            try {
                $res = Http::timeout(15)
                    ->withHeaders([
                        'apikey' => $api_key,
                        'Accept' => 'application/json',
                    ])
                    ->post($base_url, [
                        'number' => "52".$phoneNumber,
                        'textMessage' => [
                            'text' => "📢 *Estimado(a)*,\n\n" .
                                "Para más información, por favor contacte a el despacho contable Baltazar Montes:\n" .
                                "📞 *Número: +52 226 316 1354 / +52 226 316 0629 \n\n" .
                                "🕒 *Horario de atención:*\n" .
                                "📅 Lunes a Viernes de 9:00 a.m. a 6:30 p.m.\n\n" .
                                "✅ Gracias por su atención."
                        ],
                    ])
                    ->throw()
                    ->json();

                if (isset($res['error'])) {
                   
                }


            } catch (RequestException $e) {
                report($e);
            }
        }



        // Log or handle the data as needed
        \Log::info('Received Evolution webhook data:', $data);

        // Respond with a success status
        return response()->json(['status' => 'success'], 200);
    }
}
