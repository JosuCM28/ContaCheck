<?php

namespace App\Services;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class EvolutionService {

    public function sendMessage($dataEvolution) {
        $instance = config('services.evolution.instance');
        $base_url = config('services.evolution.base_url').'/message/sendMedia/'.$instance;
        $api_key = config('services.evolution.api_key');

        $number = '52'.$dataEvolution['number'];
        $pdf_data = $dataEvolution['pdf_data'];
        $concept = $dataEvolution['concept'];
        $date_raw = $dataEvolution['payment_date'];
        $date = Carbon::parse($date_raw)->timezone('America/Mexico_City')->format('d_m_Y');
        $fileName = "factura_{$date}.pdf";

        try {
            $res = Http::timeout(15)
                ->withHeaders([
                    'apikey' => $api_key,
                    'Accept' => 'application/json',
                ])
                ->post($base_url, [
                    'number' => $number,
                    'mediatype' => 'document',
                    'fileName' => $fileName,
                    'caption' => "FACTURA POR {$concept}",
                    'media' => $pdf_data,
                ])
                ->throw()
                ->json();

                if (isset($res['error'])) {
                    return false;
                }

                return true;

        } catch (RequestException $e) {
            report($e);
            return false;
        }
    }
}