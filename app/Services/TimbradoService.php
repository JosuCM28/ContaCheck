<?php

namespace App\Services;

use SoapClient;
use Exception;

class TimbradoService
{
    protected string $wsdl;
    protected string $rfc;
    protected string $apiKey;
    protected array $data;

    public function __construct(array $data)
    {
        $this->wsdl = 'https://www.facturafiel.com/websrv/servicio_timbrado_40.php?wsdl';
        $this->rfc = env('FACTURAFIEL_RFC');
        $this->apiKey = env('FACTURAFIEL_API_KEY');
        $this->data = $data;
    }

    public function timbrar(): array
    {
        try {
            $datos = $this->generarCadena();
            $cadenaEnviada = "{$this->rfc}~{$this->apiKey}~{$datos}";

            $soap = new SoapClient($this->wsdl, [
                'trace' => 1,
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'stream_context' => stream_context_create([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ])
            ]);

            $params = ['datos_enviados' => $cadenaEnviada];

            $response = $soap->__soapCall('servicio_timbrado', $params);

            if (stripos($response, 'ERROR') === 0) {
                throw new Exception("Error al timbrar: $response");
            }

            $xml = simplexml_load_string($response);

            // Registrar namespaces
            $xml->registerXPathNamespace('cfdi', 'http://www.sat.gob.mx/cfd/4');
            $xml->registerXPathNamespace('tfd', 'http://www.sat.gob.mx/TimbreFiscalDigital');

            // 1. UUID
            $timbre = $xml->xpath('//cfdi:Complemento/tfd:TimbreFiscalDigital');
            if (!$timbre || !isset($timbre[0]['UUID'])) {
                throw new Exception("No se encontró el UUID.");
            }
            $uuid = (string) $timbre[0]['UUID'];

            // 2. Total
            $totalRaw = (string) $xml['Total'];
            if (!$totalRaw) {
                throw new Exception("No se encontró el Total.");
            }
            $totalFormateado = sprintf('%017.6f', (float) $totalRaw);

            // 3. fe: últimos 8 del sello codificados en base64
            $selloCFD = (string) $timbre[0]['SelloCFD'];
            if (!$selloCFD || strlen($selloCFD) < 8) {
                throw new Exception("No se encontró o es inválido el sello CFD.");
            }
            $ultimos8 = substr($selloCFD, -8);

            $response = [
                'uuid' => $uuid,
                'total' => $totalFormateado,
                'sello' => $ultimos8,
            ];

            return $response;

        } catch (Exception $e) {
            throw new Exception("Error al conectar con FacturaFiel: " . $e->getMessage());
        }
    }

    protected function generarCadena(): string
    {
        $cadena = "";

        $cadena .= "AmbienteDePruebas=SI\n";
        $cadena .= "TipoDeComprobante=Ingreso\n";
        $cadena .= "TipoDeFormato=ReciboDeHonorarios\n";
        $cadena .= "Serie=H\n";
        $cadena .= "Exportacion=01\n";
        $cadena .= "FormaDePago={$this->data['forma_pago']}\n";
        $cadena .= "CondicionesDePago=Contado\n";
        $cadena .= "MetodoDePago=PUE\n";
        //todo: hace dinámico (opcional)
        $cadena .= "LugarExpedicion=93700\n";
        $cadena .= "SubTotal={$this->data['subtotal']}\n";
        $cadena .= "Total={$this->data['total']}\n";
        $cadena .= "Moneda=MXN\n";

        // Emisor
        //todo: hacer esto dinámico
        $cadena .= "RegimenEmisor=626\n";

        // Receptor
        $cadena .= "UsoCFDI=G03\n";
        $cadena .= "RFCReceptor={$this->data['rfcReceptor']}\n";
        $cadena .= "NombreReceptor={$this->data['nombreReceptor']}\n";
        //todo: agregar campo del código del regimén
        $cadena .= "RegimenFiscalReceptor={$this->data['regimenFiscalReceptor']}\n";
        $cadena .= "DomicilioFiscalReceptor={$this->data['domicilioFiscalReceptor']}\n"; // Código postal registrado ante el sat 
        //poner el pais del receptor
        $cadena .= "Pais=México\n";
        $cadena .= "Estado={$this->data['estado']}\n";
        //todo: agregar campo de localidad
        $cadena .= "Localidad={$this->data['localidad']}\n";
        $cadena .= "Municipio={$this->data['municipio']}\n";
        $cadena .= "Calle={$this->data['calle']}\n";
        //todo: agregar campo de colonia
        $cadena .= "Colonia={$this->data['colonia']}\n";
        //todo: agregar campo de no. exterior
        $cadena .= "NoExterior={$this->data['noExterior']}\n";
        $cadena .= "CodigoPostal={$this->data['codigoPostal']}\n";

        // Conceptos
        $cadena .= "NumeroDePartidas=1\n";
        $cadena .= "Concepto_1_Cantidad=1.00\n";
        $cadena .= "Concepto_1_Unidad=Servicio\n";
        $cadena .= "Concepto_1_UnidadSAT=E48\n";
        $cadena .= "Concepto_1_UnidadSATD=Unidad de servicio\n";
        $cadena .= "Concepto_1_ClaveSAT=84111500\n";
        $cadena .= "Concepto_1_ClaveSATD=Servicios contables\n";
        $cadena .= "Concepto_1_ObjetoImp=02\n";
        $cadena .= "Concepto_1_NoIdentificacion=001\n";
        $cadena .= "Concepto_1_Descripcion={$this->data['concepto_descripcion']}\n";
        $cadena .= "Concepto_1_ValorUnitario={$this->data['subtotal']}\n";
        $cadena .= "Concepto_1_Importe={$this->data['subtotal']}\n";
        $cadena .= "Concepto_1_Num_Impuestos_Tras=1\n";
        $cadena .= "Concepto_1_Num_Impuestos_Ret=0\n";

        // Impuestos de Retención
        $cadena .= "Concepto_1_Imp_Ret_1_Base=0.00\n";
        $cadena .= "Concepto_1_Imp_Ret_1_Impuesto=001\n";
        $cadena .= "Concepto_1_Imp_Ret_1_TipoFactor=Tasa\n";
        $cadena .= "Concepto_1_Imp_Ret_1_TasaOCuota=0.000000\n";
        $cadena .= "Concepto_1_Imp_Ret_1_Importe=0.00\n";

        // Traslados del concepto
        $cadena .= "Concepto_1_Imp_Tras_1_Base={$this->data['subtotal']}\n";
        $cadena .= "Concepto_1_Imp_Tras_1_Impuesto=002\n";
        $cadena .= "Concepto_1_Imp_Tras_1_TipoFactor=Tasa\n";
        $cadena .= "Concepto_1_Imp_Tras_1_TasaOCuota=0.160000\n";
        $cadena .= "Concepto_1_Imp_Tras_1_Importe={$this->data['descuento']}\n";

        // Retenciones del concepto

        $cadena .= "TotalDeImpuestosTrasladados={$this->data['descuento']}\n";
        $cadena .= "Num_TotalImpuestosTrasladados=1\n";
        $cadena .= "ImpuestosTrasladado1_Tasa=0.160000\n";
        $cadena .= "ImpuestosTrasladado1_Importe={$this->data['descuento']}\n";
        $cadena .= "ImpuestosTrasladado1_TipoFactor=Tasa\n";
        $cadena .= "ImpuestosTrasladado1_Impuesto=002\n";
        $cadena .= "ImpuestosTrasladado1_Base={$this->data['subtotal']}\n";

        return $cadena;
    }
}