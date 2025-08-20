<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CfdiExcelConverter extends Component
{
    use WithFileUploads;

    public string $fileName;
    public string $downloadLink = '';

    public array $xmls = [];
    public $newXmls;

    public bool $processing = false;
    public bool $completed = false;
    public int $duration = 0;

    private array $formasDePago = [
        '01' => '01 - Efectivo',
        '02' => '02 - Cheque nominativo',
        '03' => '03 - Transferencia electrónica de fondos',
        '04' => '04 -Tarjeta de crédito',
        '05' => '05 - Monedero electrónico',
        '06' => '06 - Dinero electrónico',
        '08' => '08 - Vales de despensa',
        '12' => '12 - Dación en pago',
        '13' => '13 - Pago por subrogación',
        '14' => '14 - Pago por consignación',
        '15' => '15 - Condonación',
        '17' => '17 - Compensación',
        '23' => '23 - Novación',
        '24' => '24 - Confusión',
        '25' => '25 - Remisión de deuda',
        '26' => '26 - Prescripción o caducidad',
        '27' => '27 - A satisfacción del acreedor',
        '28' => '28 - Tarjeta de débito',
        '29' => '29 - Tarjeta de servicios',
        '30' => '30 - Aplicación de anticipos',
        '31' => '31 - Intermediario de pagos',
        '99' => '99 - Por definir',
    ];

    public function mount()
    {
        $this->fileName = 'Reportes-facturas-' . now()->format('d-m-Y');
    }

    public function updatedNewXmls()
    {
        $this->validate([
            'newXmls.*' => 'file|mimetypes:text/xml,application/xml|max:2048',
        ]);

        if ($this->newXmls) {
            foreach ($this->newXmls as $file) {
                $this->xmls[] = $file;
            }
        }

        $this->newXmls = null;
    }

    public function export()
    {
        $this->processing = true;
        $this->completed = false;
        $startTime = now();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray([
            'CfdiRelacionados',
            'TipoComprobante',
            'FechaEmisionXML',
            'FechaTimbradoXML',
            'Serie',
            'Folio',
            'UUID',
            'RFC Emisor',
            'Nombre Emisor',
            'RFC Receptor',
            'Nombre Receptor',
            'UsoCFDI',
            'SubTotal',
            'Descuento',
            'Total IEPS',
            'IVA 16 Importe',
            'IVA Retenido',
            'ISR Retenido',
            'ISH',
            'Total',
            'Total Trasladados',
            'Total Retenidos',
            'Total Local Trasladado',
            'Total Local Retenido',
            'FormaDePago',
            'Metodo de Pago',
            'Conceptos',
            'RegimenFiscalReceptor',
            'DomicilioFiscalReceptor',
            // Campos adicionales al final
            'IEPS Retenido',
            'Complementos comprobante',
            'Complementos conceptos',

        ], null, 'A1');

        $highestColumn = $sheet->getHighestColumn();

        $sheet->getStyle("A1:{$highestColumn}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD'], // Azul claro profesional
            ],
        ]);

        $row = 2;

        foreach ($this->xmls as $xml) {
            $xmlContent = simplexml_load_string($xml->get());
            $namespaces = $xmlContent->getNamespaces(true);

            $xmlContent->registerXPathNamespace('cfdi', $namespaces['cfdi'] ?? 'http://www.sat.gob.mx/cfd/4');
            $xmlContent->registerXPathNamespace('tfd', $namespaces['tfd'] ?? 'http://www.sat.gob.mx/TimbreFiscalDigital');

            $comprobante = $xmlContent->attributes();

            $emisor = $xmlContent->xpath('//cfdi:Emisor')[0]?->attributes() ?? [];
            $receptor = $xmlContent->xpath('//cfdi:Receptor')[0]?->attributes() ?? [];

            $uuid = $fechaTimbrado = '';
            $tfd = $xmlContent->xpath('//cfdi:Complemento/tfd:TimbreFiscalDigital')[0] ?? null;
            if ($tfd) {
                $attrs = $tfd->attributes();
                $uuid = (string) ($attrs['UUID'] ?? '');
                $fechaTimbrado = (string) ($attrs['FechaTimbrado'] ?? '');
            }

            $tipoRelacion = '';
            $relacionado = $xmlContent->xpath('//cfdi:CfdiRelacionados')[0] ?? null;
            if ($relacionado) {
                $tipoRelacion = (string) ($relacionado->attributes()['TipoRelacion'] ?? '');
            }

            $complementos = [];
            foreach ($xmlContent->xpath('//cfdi:Complemento/*') as $comp) {
                $complementos[] = $comp->getName();
            }

            $conceptos = [];
            #$claveProdServ = $cantidad = $claveUnidad = $unidad = $valorUnitario = $importe = [];
            $complementosConceptos = [];
            foreach ($xmlContent->xpath('//cfdi:Conceptos/cfdi:Concepto') as $concepto) {
                $attrs = $concepto->attributes();
                $conceptos[] = (string) ($attrs['Descripcion'] ?? '');
                #$claveProdServ[] = (string) ($attrs['ClaveProdServ'] ?? '');
                #$cantidad[] = (string) ($attrs['Cantidad'] ?? '');
                #$claveUnidad[] = (string) ($attrs['ClaveUnidad'] ?? '');
                #$unidad[] = (string) ($attrs['Unidad'] ?? '');
                #$valorUnitario[] = (string) ($attrs['ValorUnitario'] ?? '');
                #$importe[] = (string) ($attrs['Importe'] ?? '');

                foreach ($concepto->children() as $child) {
                    $complementosConceptos[] = $child->getName();
                }
            }

            $trasladados = $retenidos = 0;
            $iva16 = $isrRet = $ivaRet = $iepsRet = '';

            $totalIEPS = '0';
            $ish = '0';
            $totalLocalTrasladado = '0';
            $totalLocalRetenido = '0';

            foreach ($xmlContent->xpath('//cfdi:Complemento/*') as $comp) {
                $compName = $comp->getName();
                if (stripos($compName, 'ImpuestosLocales') !== false) {
                    $attrs = $comp->attributes();
                    $totalLocalTrasladado = (string) ($attrs['TotaldeTraslados'] ?? '0');
                    $totalLocalRetenido = (string) ($attrs['TotaldeRetenciones'] ?? '0');

                    foreach ($comp->children() as $child) {
                        if ($child->getName() === 'TrasladosLocales') {
                            $childAttrs = $child->attributes();
                            if (isset($childAttrs['ImpLocTrasladado']) && strtoupper((string) $childAttrs['ImpLocTrasladado']) === 'ISH') {
                                $ish = (string) ($childAttrs['Importe'] ?? '0');
                            }
                        }
                    }
                }
            }



            $impuestos = $xmlContent->children($namespaces['cfdi'])->Impuestos ?? null;

            if ($impuestos) {
                $impAttrs = $impuestos->attributes();
                $trasladados = (string) ($impAttrs['TotalImpuestosTrasladados'] ?? 0);
                $retenidos = (string) ($impAttrs['TotalImpuestosRetenidos'] ?? 0);

                foreach ($impuestos->Retenciones->Retencion ?? [] as $ret) {
                    $attrs = $ret->attributes();
                    $imp = (string) $attrs['Impuesto'];
                    $impVal = (string) $attrs['Importe'];
                    if ($imp === '001')
                        $isrRet = $impVal;
                    if ($imp === '002')
                        $ivaRet = $impVal;
                    if ($imp === '003')
                        $iepsRet = $impVal;
                }

                foreach ($impuestos->Traslados->Traslado ?? [] as $tra) {
                    $attrs = $tra->attributes();
                    $impuesto = (string) ($attrs['Impuesto'] ?? '');
                    $importe = (string) ($attrs['Importe'] ?? '');

                    if ($impuesto === '002' && (string) ($attrs['TasaOCuota'] ?? '') === '0.160000') {
                        $iva16 = $importe;
                    }

                    if ($impuesto === '003') {
                        $totalIEPS = $importe;
                    }
                }

            }



            $sheet->fromArray([
                $tipoRelacion,
                (string) ($comprobante['TipoDeComprobante'] ?? ''),
                (string) ($comprobante['Fecha'] ?? ''),
                $fechaTimbrado,
                (string) ($comprobante['Serie'] ?? ''),
                (string) ($comprobante['Folio'] ?? ''),
                $uuid,
                (string) ($emisor['Rfc'] ?? ''),
                (string) ($emisor['Nombre'] ?? ''),
                (string) ($receptor['Rfc'] ?? ''),
                (string) ($receptor['Nombre'] ?? ''),
                (string) ($receptor['UsoCFDI'] ?? ''),
                (string) ($comprobante['SubTotal'] ?? ''),
                (string) ($comprobante['Descuento'] ?? ''),
                $totalIEPS,
                $iva16,
                $ivaRet,
                $isrRet,
                $ish,
                (string) ($comprobante['Total'] ?? ''),
                $trasladados,
                $retenidos,
                $totalLocalTrasladado,
                $totalLocalRetenido,
                $this->formasDePago[(string) ($comprobante['FormaPago'] ?? '')] ?? (string) ($comprobante['FormaPago'] ?? ''),
                (string) ($comprobante['MetodoPago'] ?? ''),
                implode(' | ', $conceptos),
                (string) ($receptor['RegimenFiscalReceptor'] ?? ''),
                (string) ($receptor['DomicilioFiscalReceptor'] ?? ''),
                // Campos adicionales al final
                $iepsRet,
                implode(' | ', $complementos),
                implode(' | ', $complementosConceptos),
            ], null, 'A' . $row);

            $row++;
        }

        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestColumn());
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }

        $uuid = substr(Str::uuid(), -6);
        $this->fileName = 'Reportes-facturas-' . now()->format('d-m-Y') . '-' . $uuid . '.xlsx';

        $relativePath = "cfdi_excels/{$this->fileName}";
        Storage::disk('public')->makeDirectory('cfdi_excels');
        $tempPath = Storage::disk('public')->path($relativePath);

        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);

        $this->downloadLink = Storage::url($relativePath);
        $this->duration = now()->diffInSeconds($startTime);
        $this->completed = true;
    }




    public function removeXml($index)
    {
        unset($this->xmls[$index]);
        $this->xmls = array_values($this->xmls);
    }

    public function render()
    {
        return view('livewire.cfdi-excel-converter');
    }
}
