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
            'CfdiRelacionados', 'UUID', 'Serie', 'Folio', 'TipoComprobante', 'FechaTimbradoXML', 'FechaEmisionXML',
            'RFC Emisor', 'Nombre Emisor', 'RegimenFiscal', 'RFC Receptor', 'Nombre Receptor', 'UsoCFDI',
            'RegimenFiscalReceptor', 'DomicilioFiscalReceptor', 'FormaDePago', 'Metodo de Pago', 'Complementos comprobante',
            'Conceptos', 'ClaveProdServ', 'Cantidad', 'ClaveUnidad', 'Unidad', 'ValorUnitario', 'Importe',
            'Complementos conceptos', 'SubTotal', 'Descuento', 'Total Trasladados', 'Total Retenidos',
            'Total', 'IVA 16 Importe', 'ISR Retenido', 'IVA Retenido', 'IEPS Retenido'
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
            $claveProdServ = $cantidad = $claveUnidad = $unidad = $valorUnitario = $importe = [];
            $complementosConceptos = [];
            foreach ($xmlContent->xpath('//cfdi:Conceptos/cfdi:Concepto') as $concepto) {
                $attrs = $concepto->attributes();
                $conceptos[] = (string) ($attrs['Descripcion'] ?? '');
                $claveProdServ[] = (string) ($attrs['ClaveProdServ'] ?? '');
                $cantidad[] = (string) ($attrs['Cantidad'] ?? '');
                $claveUnidad[] = (string) ($attrs['ClaveUnidad'] ?? '');
                $unidad[] = (string) ($attrs['Unidad'] ?? '');
                $valorUnitario[] = (string) ($attrs['ValorUnitario'] ?? '');
                $importe[] = (string) ($attrs['Importe'] ?? '');

                foreach ($concepto->children() as $child) {
                    $complementosConceptos[] = $child->getName();
                }
            }

            $trasladados = $retenidos = 0;
            $iva16 = $isrRet = $ivaRet = $iepsRet = '';

            $impuestos = $xmlContent->children($namespaces['cfdi'])->Impuestos ?? null;

            if ($impuestos) {
                $impAttrs = $impuestos->attributes();
                $trasladados = (string) ($impAttrs['TotalImpuestosTrasladados'] ?? 0);
                $retenidos = (string) ($impAttrs['TotalImpuestosRetenidos'] ?? 0);

                foreach ($impuestos->Retenciones->Retencion ?? [] as $ret) {
                    $attrs = $ret->attributes();
                    $imp = (string) $attrs['Impuesto'];
                    $impVal = (string) $attrs['Importe'];
                    if ($imp === '001') $isrRet = $impVal;
                    if ($imp === '002') $ivaRet = $impVal;
                    if ($imp === '003') $iepsRet = $impVal;
                }

                foreach ($impuestos->Traslados->Traslado ?? [] as $tra) {
                    $attrs = $tra->attributes();
                    if ((string) $attrs['Impuesto'] === '002' && (string) $attrs['TasaOCuota'] === '0.160000') {
                        $iva16 = (string) $attrs['Importe'];
                    }
                }
            }

            $sheet->fromArray([
                $tipoRelacion,
                $uuid,
                (string) ($comprobante['Serie'] ?? ''),
                (string) ($comprobante['Folio'] ?? ''),
                (string) ($comprobante['TipoDeComprobante'] ?? ''),
                $fechaTimbrado,
                (string) ($comprobante['Fecha'] ?? ''),
                (string) ($emisor['Rfc'] ?? ''),
                (string) ($emisor['Nombre'] ?? ''),
                (string) ($emisor['RegimenFiscal'] ?? ''),
                (string) ($receptor['Rfc'] ?? ''),
                (string) ($receptor['Nombre'] ?? ''),
                (string) ($receptor['UsoCFDI'] ?? ''),
                (string) ($receptor['RegimenFiscalReceptor'] ?? ''),
                (string) ($receptor['DomicilioFiscalReceptor'] ?? ''),
                (string) ($comprobante['FormaPago'] ?? ''),
                (string) ($comprobante['MetodoPago'] ?? ''),
                implode(' | ', $complementos),
                implode(' | ', $conceptos),
                implode(' | ', $claveProdServ),
                implode(' | ', $cantidad),
                implode(' | ', $claveUnidad),
                implode(' | ', $unidad),
                implode(' | ', $valorUnitario),
                implode(' | ', $importe),
                implode(' | ', $complementosConceptos),
                (string) ($comprobante['SubTotal'] ?? ''),
                (string) ($comprobante['Descuento'] ?? ''),
                $trasladados,
                $retenidos,
                (string) ($comprobante['Total'] ?? ''),
                $iva16,
                $isrRet,
                $ivaRet,
                $iepsRet,
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
