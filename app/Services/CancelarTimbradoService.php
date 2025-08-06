<?php

namespace App\Services;

use Exception;
use SoapClient;
use App\Models\Receipt;
use Illuminate\Support\Facades\Redirect;

class CancelarTimbradoService
{
    protected string $wsdl = "https://www.facturafiel.com/websrv/servicio_cancelacion.php?wsdl";

    public function cancelarCFDI($id, string $motivo = "02", string $uuidSustitucion = "")
    {
        $receipt = Receipt::find($id);
        
        if ($receipt && $receipt->is_timbred == false) {
            $receipt->status = 'CANCELADO';
            $receipt->save();
            return redirect()->route('receipt.show', ['identificator' => $receipt->id])->with('success','Recibo cancelado exitosamente.');
        }

        try {
            $client = new SoapClient($this->wsdl, [
                'trace' => 1,
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'stream_context' => stream_context_create([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true,
                    ]
                ])
            ]);

            $rfcEmisor = env('FACTURAFIEL_RFC');
            $apiKey = env('FACTURAFIEL_API_KEY');
            $folioUUID = $receipt->uuid;

            $cadenaEnviada = "{$rfcEmisor}~{$apiKey}~{$folioUUID}~{$motivo}~{$uuidSustitucion}";
            $params = ['datos_enviados' => $cadenaEnviada];

            $respuesta = $client->__soapCall('servicio_cancelacion', $params); 

            $respuesta = trim($respuesta);
            $exito = stripos($respuesta, "FUE CANCELADO EXITOSAMENTE") !== false;

            if ($exito) {
                if ($receipt) {
                    $receipt->is_timbred = false;
                    $receipt->status = 'CANCELADO';
                    $receipt->save();
                }
            }

            return redirect()->route('receipt.show', ['identificator' => $receipt->id])->with('success','Recibo cancelado exitosamente.');
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al cancelar CFDI: ' . $e->getMessage(),
            ];
        }
    }
}
