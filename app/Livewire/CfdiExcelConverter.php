<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Services\DeepSeekService;

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

    public function mount(): void
    {
        $this->fileName = 'Reportes-facturas-' . now()->format('d-m-Y');
    }

    public function updatedNewXmls(): void
    {
        if (!$this->newXmls) {
            return;
        }

        Storage::disk('local')->makeDirectory('cfdi_tmp');

        foreach ($this->newXmls as $file) {
            if (!$file->isValid()) {
                continue;
            }

            $extension = strtolower($file->getClientOriginalExtension());
            if ($extension !== 'xml') {
                continue;
            }

            $originalName = $file->getClientOriginalName();
            $diskPath     = $file->storeAs('cfdi_tmp', uniqid('cfdi_', true) . '.xml', 'local');

            $this->xmls[] = [
                'name' => $originalName,
                'path' => $diskPath,
            ];
        }

        $this->newXmls = null;
    }

    public function export(): void
    {
        $this->processing = true;
        $this->completed  = false;
        $startTime        = now();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        // ── Cabeceras ────────────────────────────────────────────────────────────
        // COLUMNAS COMENTADAS (datos calculados, disponibles para reactivar):
        // 'CfdiRelacionados',
        // 'FechaEmisionXML',
        // 'FechaTimbradoXML',
        // 'Serie',
        // 'Folio',
        // 'UUID',
        // 'UsoCFDI',
        // 'SubTotal',
        // 'Descuento',
        // 'Total IEPS',
        // 'IVA 16 Importe',
        // 'IVA Retenido',
        // 'ISR Retenido',
        // 'ISH',
        // 'Total',
        // 'Total Trasladados',
        // 'Total Retenidos',
        // 'Total Local Trasladado',
        // 'Total Local Retenido',
        // 'FormaDePago',
        // 'Metodo de Pago',
        // 'Conceptos',
        // 'Descripción',
        // 'RegimenFiscalReceptor',
        // 'IEPS Retenido',
        // 'Complementos comprobante',
        // 'Complementos concepto',

        $headers = [
            // ── COLUMNAS ACTIVAS (orden solicitado) ──────────────────────────────
            'Tipo de operación',
            'RFC Emisor',
            'Nombre Emisor',
            'RFC Receptor',
            'Nombre Receptor',
            'Clave de la entidad Federativa',
            'Lugar de Expedición',
            'Tipo de Producto',
            'Empresa tabacalera',
            'Clave generica del producto',
            'Graduación',
            'IEPS 3%',
            'IEPS 6%',
            'IEPS 7%',
            'IEPS 8%',
            'IEPS 9%',
            'IEPS 16%',
            'IEPS 26.5%',
            'IEPS 30%',
            'IEPS 53%',
            'IEPS 160%',
            'Volumen total de enajenación por tipo de producto',
            'Empaque',
            'Presentacion',
            'Unidad de Medida',
            'Valor de la operación (antes de impuestos)',
            'Valor de la operación valuada al precio de venta al detallista',
            'IEPS Pagado / Trasladado',
            'IVA Pagado / Trasladado',
            'IEPS trasladado por la aplicación de la cuota específica de cigarros enajenados',
            'IEPS trasladado por la aplicación de la cuota específica por el peso total en gramos de los puros y otros tabacos labrados enajenados',
            'IEPS trasladado por la aplicación de la cuota específica por el peso total en gramos de los puros y otros tabacos labrados hechos enteramente a mano enajenados',
            'IEPS trasladado por la aplicación de la cuota de bebidas saborizadas',
            'IEPS trasladado por la aplicación de la cuota de bebidas energetizantes',
            'IEPS TRASLADADO POR LA APLICACIÓN DE LA CUOTA DE BEBIDAS SABORIZADAS',
            'IEPS TRASLADADO POR LA APLICACIÓN DE LA CUOTA DE BEBIDAS CON ENDULCORANTES AÑADIDOS',
            'IEPS TRASLADADO POR LA APLICACIÓN DE LA CUOTA DE BEBIDAS ENERGETIZANTES O CONCENTRADOS, POLVOS Y JARABES PARA PREPARAR BEBIDAS ENERGETIZANTES CON AZÚCARES AÑADIDOS',
            'IEPS TRASLADADO POR LA APLICACIÓN DE LA CUOTA DE BEBIDAS ENERGETIZANTES O CONCENTRADOS, POLVOS Y JARABES PARA PREPARAR BEBIDAS ENERGETIZANTES CON ENDULCORANTES AÑADIDOS',
            'IEPS pagados en la importación por la aplicación de la cuota específica de cigarros',
            'IEPS pagados en la importación por la aplicación de la cuota específica de los puros y otros tabacos labrados, ASÍ COMO DE OTROS PRODUCTOS QUE CONTENGAN NICOTINA',
            'IEPS pagados en la importación por la aplicación de la cuota específica de los puros y otros tabacos labrados hechos enteramente a mano',
            'IEPS pagado en la importación por la aplicación de la cuota de bebidas saborizadas',
            'IEPS pagado en la importación por la aplicación de la cuota de bebidas energetizantes',
            'IEPS pagado en la importación por la aplicación de la cuota de bebidas saborizadas CON AZUCARES AÑADIDOS',
            'IEPS pagado en la importación por la aplicación de la cuota de bebidas saborizadas CON ENDULCORANTES AÑADIDOS',
            'IEPS pagado en la importación por la aplicación de la cuota de bebidas energetizantes CON AZUCARES AÑADIDOS',
            'IEPS pagado en la importación por la aplicación de la cuota de bebidas energetizantes CON ENDULCORANTES AÑADIDOS',
        ];

        $sheet->fromArray($headers, null, 'A1');

        $highestColumn = $sheet->getHighestColumn();
        $sheet->getStyle("A1:{$highestColumn}1")->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD'],
            ],
        ]);

        // ── Paso 1: parsear todos los XMLs y recolectar filas ───────────────────
        // Solo se extraen los campos que usan las columnas activas del reporte.
        // Los campos comentados se dejaron documentados para reactivar fácil.
        $pendingRows        = [];
        $uniqueDescriptions = [];
        $uniqueCps          = []; // CPs únicos para resolver con DeepSeek

        foreach ($this->xmls as $xml) {

            $rawXml     = Storage::disk('local')->get($xml['path']);
            $xmlContent = $rawXml ? @simplexml_load_string($rawXml) : false;

            // Saltar XMLs inválidos, vacíos o de esquema desconocido
            if ($xmlContent === false || $xmlContent === null) {
                continue;
            }

            $namespaces = $xmlContent->getNamespaces(true);
            $cfdiNs     = $namespaces['cfdi'] ?? 'http://www.sat.gob.mx/cfd/4';

            $xmlContent->registerXPathNamespace('cfdi', $cfdiNs);

            $comprobante     = $xmlContent->attributes();
            $tipoComprobante = (string) ($comprobante['TipoDeComprobante'] ?? '');

            // Campos del nivel comprobante que usan columnas activas
            $emisor   = $xmlContent->xpath('//cfdi:Emisor')[0]?->attributes()   ?? [];
            $receptor = $xmlContent->xpath('//cfdi:Receptor')[0]?->attributes() ?? [];

            $rfcEmisor      = (string) ($emisor['Rfc']                       ?? '');
            $nomEmisor      = (string) ($emisor['Nombre']                    ?? '');
            $rfcReceptor    = (string) ($receptor['Rfc']                     ?? '');
            $nomReceptor    = (string) ($receptor['Nombre']                  ?? '');
            $domFiscal      = (string) ($receptor['DomicilioFiscalReceptor'] ?? '');
            $lugarExpedicion = (string) ($comprobante['LugarExpedicion']     ?? '');

            // Campos comentados (no usados en columnas activas — reactivar si se necesitan):
            // $uuid = $fechaTimbrado = '';
            // $tfd  = $xmlContent->xpath('//cfdi:Complemento/tfd:TimbreFiscalDigital')[0] ?? null;
            // if ($tfd) { $uuid = $tfd->attributes()['UUID']; $fechaTimbrado = $tfd->attributes()['FechaTimbrado']; }
            // $tipoRelacion = (string) ($xmlContent->xpath('//cfdi:CfdiRelacionados')[0]?->attributes()['TipoRelacion'] ?? '');
            // $complementos = array_map(fn($c) => $c->getName(), $xmlContent->xpath('//cfdi:Complemento/*'));
            // $ish = $totalLocalTrasladado = $totalLocalRetenido = '0'; // ImpuestosLocales
            // $impuestos   = $xmlContent->children($cfdiNs)->Impuestos ?? null;
            // $trasladados = $retenidos = $iva16 = $isrRet = $ivaRet = $iepsRet = $totalIEPS = '';

            // Conceptos — si no hay, no hay nada que procesar
            $conceptosNodos = $xmlContent->xpath('//cfdi:Conceptos/cfdi:Concepto');
            if (empty($conceptosNodos)) {
                continue;
            }

            // Plantilla IEPS y claves extraídas UNA SOLA VEZ por XML (no por concepto)
            $iepsTemplate = [
                '0.030000' => '',   // 3%
                '0.060000' => '',   // 6%
                '0.070000' => '',   // 7%
                '0.080000' => '',   // 8%
                '0.090000' => '',   // 9%
                '0.160000' => '',   // 16%
                '0.265000' => '',   // 26.5%
                '0.300000' => '',   // 30%
                '0.530000' => '',   // 53%
                '1.600000' => '',   // 160%
            ];
            $iepsKeys = array_keys($iepsTemplate); // calculado una vez, reutilizado por cada concepto

            // Para tipo P (pago) u otros sin datos IEPS, aún creamos filas
            // pero no mandamos nada a DeepSeek
            foreach ($conceptosNodos as $concepto) {
                $attrs       = $concepto->attributes();
                $descripcion = (string) ($attrs['Descripcion'] ?? '');
                $objetoImp   = (string) ($attrs['ObjetoImp']   ?? '');

                // ── IEPS por tasa (columnas 10-19) ───────────────────────────────
                $iepsColumns = $iepsTemplate; // reset rápido (copia de array ya construido)

                $baseIva     = '';
                $importeIeps = '';
                $importeIva  = '';
                $graduacion  = '000'; // 000=sin alcohol, 001=26.5%, 002=30%, 003=53%

                // Solo leer impuestos del concepto si el concepto los tiene
                if ($objetoImp !== '01') {
                    $impuestosConcepto = $concepto->children($cfdiNs)->Impuestos ?? null;
                    if ($impuestosConcepto) {
                        foreach ($impuestosConcepto->Traslados->Traslado ?? [] as $traslado) {
                            $tAttrs   = $traslado->attributes();
                            $impuesto = (string) ($tAttrs['Impuesto']   ?? '');
                            $tasa     = (string) ($tAttrs['TasaOCuota'] ?? '');
                            $importe  = (string) ($tAttrs['Importe']    ?? '');

                            if ($impuesto === '003') {
                                if ($tasa !== '') {
                                    $nearestKey = $this->findNearestIepsKey((float) $tasa, $iepsKeys);
                                    if ($nearestKey !== null) {
                                        $iepsColumns[$nearestKey] = $importe;
                                    }

                                    // Graduación alcohólica según tasa IEPS
                                    $tasaFloat = (float) $tasa;
                                    if (abs($tasaFloat - 0.265) < 0.001) {
                                        $graduacion = '001'; // 26.5% → grado bajo
                                    } elseif (abs($tasaFloat - 0.300) < 0.001) {
                                        $graduacion = '002'; // 30%   → grado medio
                                    } elseif (abs($tasaFloat - 0.530) < 0.001) {
                                        $graduacion = '003'; // 53%   → grado alto
                                    }
                                }
                                $importeIeps = $importe;
                            }
                            if ($impuesto === '002') {
                                $baseIva    = (string) ($tAttrs['Base'] ?? '');
                                $importeIva = $importe;
                            }
                        }
                    }
                }

                // ¿Necesita clasificación IA?
                // Cualquier concepto gravado que no sea complemento de pago
                $necesitaIA = $tipoComprobante !== 'P'
                    && $objetoImp !== '01';

                if ($necesitaIA) {
                    $uniqueDescriptions[$descripcion] = true;
                }

                // Registrar CPs únicos para resolución por IA
                if ($domFiscal !== '') {
                    $uniqueCps[$domFiscal] = true;
                }
                if ($lugarExpedicion !== '') {
                    $uniqueCps[$lugarExpedicion] = true;
                }

                $pendingRows[] = [
                    // Campos activos en columnas del reporte
                    'rfcEmisor'       => $rfcEmisor,
                    'nomEmisor'       => $nomEmisor,
                    'rfcReceptor'     => $rfcReceptor,
                    'nomReceptor'     => $nomReceptor,
                    'domFiscal'       => $domFiscal,
                    'lugarExpedicion' => $lugarExpedicion,
                    'descripcion' => $descripcion,
                    'cantidad'    => (string) ($attrs['Cantidad'] ?? ''),
                    'claveUnidad' => (string) ($attrs['ClaveUnidad'] ?? ''),
                    'unidad'      => (string) ($attrs['Unidad']      ?? ''),
                    'iepsColumns' => array_values($iepsColumns),
                    'baseIva'     => $baseIva,
                    'importeIeps' => $importeIeps,
                    'importeIva'  => $importeIva,
                    'graduacion'  => $graduacion,
                    'necesitaIA'  => $necesitaIA,
                    // Campos comentados (reactivar cuando se necesiten):
                    // 'tipoComprobante' => $tipoComprobante,
                    // 'conceptos'       => implode(' | ', $todasDescripciones),
                    // 'regimenRec'      => (string)($receptor['RegimenFiscalReceptor'] ?? ''),
                    // 'iepsRet'         => $iepsRet,
                    // 'complementos'    => implode(' | ', $complementos),
                    // 'uuid'            => $uuid,
                    // 'fechaEmision'    => (string)($comprobante['Fecha'] ?? ''),
                    // 'fechaTimbrado'   => $fechaTimbrado,
                    // 'serie'           => (string)($comprobante['Serie'] ?? ''),
                    // 'folio'           => (string)($comprobante['Folio'] ?? ''),
                    // 'subTotal'        => (string)($comprobante['SubTotal'] ?? ''),
                    // 'total'           => (string)($comprobante['Total'] ?? ''),
                    // 'descuento'       => (string)($comprobante['Descuento'] ?? ''),
                    // 'formaPago'       => $this->formasDePago[...],
                    // 'metodoPago'      => (string)($comprobante['MetodoPago'] ?? ''),
                    // 'iva16'           => $iva16,
                    // 'ivaRet'          => $ivaRet,
                    // 'isrRet'          => $isrRet,
                    // 'totalIEPS'       => $totalIEPS,
                    // 'trasladados'     => $trasladados,
                    // 'retenidos'       => $retenidos,
                    // 'ish'             => $ish,
                ];
            }
        }

        // ── Paso 2: clasificar descripciones Y resolver CPs en paralelo ─────────
        $descriptions = array_keys($uniqueDescriptions);
        $cpList       = array_keys($uniqueCps);

        // Ambas llamadas se lanzan en paralelo dentro de classifyAllAtOnce
        // aprovechando el pool Guzzle async que ya existe.
        [$allClassifications, $cpCodigos] = $this->classifyAllAtOnce($descriptions, $cpList);

        // ── Paso 3: escribir las filas en el Excel ───────────────────────────────
        // Array de filas: se acumulan todas y se escriben en UNA sola llamada a PhpSpreadsheet
        $excelRows  = [];
        $ceroEsp18  = array_fill(0, 18, '0'); // 18 columnas IEPS especiales, siempre en 0

        // ── Paso 3a: agrupar pendingRows por descripción ─────────────────────
        // Suma valores numéricos; los campos de clasificación se toman del primer registro.
        $grouped = []; // [descripcion => acumulado]

        foreach ($pendingRows as $entry) {
            $desc = $entry['descripcion'];

            if (!isset($grouped[$desc])) {
                // Primer aparición: guardar campos de metadatos + iniciar acumuladores
                $grouped[$desc] = [
                    'rfcEmisor'       => $entry['rfcEmisor'],
                    'nomEmisor'       => $entry['nomEmisor'],
                    'rfcReceptor'     => $entry['rfcReceptor'],
                    'nomReceptor'     => $entry['nomReceptor'],
                    'domFiscal'       => $entry['domFiscal'],
                    'lugarExpedicion' => $entry['lugarExpedicion'],
                    'descripcion'     => $desc,
                    'necesitaIA'      => $entry['necesitaIA'],
                    'graduacion'      => $entry['graduacion'],
                    'claveUnidad'     => $entry['claveUnidad'],
                    'unidad'          => $entry['unidad'],
                    'cantidad'        => (float) ($entry['cantidad']    ?: 0),
                    'baseIva'         => (float) ($entry['baseIva']     ?: 0),
                    'importeIeps'     => (float) ($entry['importeIeps'] ?: 0),
                    'importeIva'      => (float) ($entry['importeIva']  ?: 0),
                    'iepsColumns'     => array_map(fn($v) => (float) ($v ?: 0), $entry['iepsColumns']),
                ];
            } else {
                // Apariciones posteriores: sumar valores numéricos
                $grouped[$desc]['cantidad']    += (float) ($entry['cantidad']    ?: 0);
                $grouped[$desc]['baseIva']     += (float) ($entry['baseIva']     ?: 0);
                $grouped[$desc]['importeIeps'] += (float) ($entry['importeIeps'] ?: 0);
                $grouped[$desc]['importeIva']  += (float) ($entry['importeIva']  ?: 0);

                foreach ($entry['iepsColumns'] as $i => $val) {
                    $grouped[$desc]['iepsColumns'][$i] += (float) ($val ?: 0);
                }

                // Si cualquier aparición necesita IA, el grupo la necesita
                if ($entry['necesitaIA']) {
                    $grouped[$desc]['necesitaIA'] = true;
                }

                // Graduación: prevalece la más alta del grupo
                if ($entry['graduacion'] > $grouped[$desc]['graduacion']) {
                    $grouped[$desc]['graduacion'] = $entry['graduacion'];
                }

                // Unidad/ClaveUnidad: conservar el primer valor no vacío del grupo
                if ($grouped[$desc]['claveUnidad'] === '' && $entry['claveUnidad'] !== '') {
                    $grouped[$desc]['claveUnidad'] = $entry['claveUnidad'];
                }
                if ($grouped[$desc]['unidad'] === '' && $entry['unidad'] !== '') {
                    $grouped[$desc]['unidad'] = $entry['unidad'];
                }
            }
        }

        // ── Paso 3b: construir filas Excel desde el concentrado ──────────────
        foreach ($grouped as $desc => $entry) {
            // Clasificación IA (una sola vez por descripción única)
            if ($entry['necesitaIA']) {
                $cls           = $allClassifications[$desc] ?? [];
                $tipoProd      = $cls['tipo']     ?? '000';
                $claveGenerica = $cls['generica'] ?? '000';
                $empaque       = $cls['empaque']  ?? '000';
                $unidadMedida  = $cls['unidad']   ?? '000';
            } else {
                $tipoProd      = '000';
                $claveGenerica = '000';
                $empaque       = '000';
                $unidadMedida  = '000';
            }

            // Formatear columnas IEPS acumuladas ('0' cuando el total es cero)
            $iepsColumnsFmt = array_map(
                fn($v) => $v != 0 ? (string) $v : '0',
                $entry['iepsColumns']
            );

            // IEPS Pagado / Trasladado: sólo números enteros (se redondea al entero más cercano)
            $importeIepsFmt = (string) (int) round($entry['importeIeps']);
            $importeIvaFmt  = $entry['importeIva']  != 0 ? (string) $entry['importeIva']  : '0';
            // Valor de la operación (antes de impuestos): importe redondeado a 2 decimales
            $baseIvaFmt     = number_format(round($entry['baseIva'], 2), 2, '.', '');
            $cantidadFmt    = $entry['cantidad']    != 0 ? (string) $entry['cantidad']    : '0';

            // Empaque y Presentación derivados del XML (ClaveUnidad / Unidad del concepto)
            $empaque      = $this->resolveEmpaque($entry['claveUnidad'], $entry['unidad'], $empaque);
            // Presentación: un solo decimal
            $presentacion = number_format(
                round($this->resolvePresentacion($entry['unidad'], $entry['descripcion']), 1),
                1,
                '.',
                ''
            );

            // Resolver clave de entidad federativa y lugar de expedición desde mapa IA
            $claveEntidad    = $cpCodigos[$entry['domFiscal']]       ?? '00';
            $claveExpedicion = $cpCodigos[$entry['lugarExpedicion']] ?? '00';

            // ── Fila en el orden solicitado ───────────────────────────────────
            $excelRows[] = array_merge(
                [
                    '00',                  // 1  Tipo de operación
                    $entry['rfcEmisor']   ?: '00', // 2  RFC Emisor
                    $entry['nomEmisor']   ?: '00', // 3  Nombre Emisor
                    $entry['rfcReceptor'] ?: '00', // 4  RFC Receptor
                    $entry['nomReceptor'] ?: '00', // 5  Nombre Receptor
                    $claveEntidad,                 // 6  Clave entidad federativa
                    $claveExpedicion,              // 7  Lugar de Expedición
                    $tipoProd,                     // 8  Tipo de Producto
                    '00',                  // 9  Empresa tabacalera
                    $claveGenerica,        // 10 Clave genérica del producto
                    $entry['graduacion'],  // 11 Graduación alcohólica
                ],
                $iepsColumnsFmt,           // 12-21  IEPS 3% … 160% (10 columnas, acumuladas)
                [
                    $cantidadFmt,          // 21  Volumen total de enajenación (acumulado)
                    $empaque,              // 22  Empaque (derivado del XML)
                    $presentacion,         // 23  Presentación (un decimal)
                    $unidadMedida,         // 24  Unidad de Medida
                    $baseIvaFmt,           // 25  Valor de la operación (antes de impuestos, acumulado)
                    '0',                   // 26  Valor valuada precio detallista
                    $importeIepsFmt,       // 27  IEPS Pagado / Trasladado (acumulado)
                    $importeIvaFmt,        // 28  IVA Pagado / Trasladado (acumulado)
                ],
                $ceroEsp18                 // 18 columnas IEPS especiales (siempre en 0)
                // COLUMNAS COMENTADAS (reactivar cuando se necesiten):
                // $entry['tipoComprobante'], $entry['uuid'], $entry['fechaEmision'],
                // $entry['serie'], $entry['folio'], $entry['subTotal'], $entry['total'],
                // $entry['descuento'], $entry['formaPago'], $entry['metodoPago'],
                // $entry['iva16'], $entry['ivaRet'], $entry['isrRet'], $entry['iepsRet'],
                // $entry['totalIEPS'], $entry['trasladados'], $entry['retenidos'],
                // $entry['ish'], $entry['regimenRec'], $entry['complementos'],
            );
        }

        // Escritura masiva: UNA sola llamada en lugar de N llamadas (una por fila)
        if (!empty($excelRows)) {
            $sheet->fromArray($excelRows, null, 'A2');
        }

        // Auto-ajuste de columnas
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(
            $sheet->getHighestColumn()
        );
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }

        // Guardar archivo
        $uuidSuffix       = substr(Str::uuid(), -6);
        $this->fileName   = 'Reportes-facturas-' . now()->format('d-m-Y') . '-' . $uuidSuffix . '.xlsx';
        $relativePath     = "cfdi_excels/{$this->fileName}";

        Storage::disk('public')->makeDirectory('cfdi_excels');
        $tempPath = Storage::disk('public')->path($relativePath);

        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);

        $this->downloadLink = Storage::url($relativePath);
        $this->duration     = now()->diffInSeconds($startTime);
        $this->completed    = true;

        // Limpiar archivos XML temporales del disco (ya fueron procesados)
        foreach ($this->xmls as $xml) {
            Storage::disk('local')->delete($xml['path']);
        }
    }

    /**
     * Clasifica un listado de descripciones usando DeepSeek contra un catálogo SAT.
     * Una sola llamada a la API por catálogo (lote completo).
     *
     * @param  string[]  $descriptions
     * @param  string    $catalogPath   Ruta relativa a resource_path()
     * @return array<string, string>    Mapa [descripcion => codigo]
     */

    // ─────────────────────────────────────────────────────────────────────────
    // CLASIFICACIÓN ÚNICA: reemplaza las 5 llamadas individuales con 1 sola.
    // Procesa en lotes de 30 para soportar cientos de productos.
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Clasifica descripciones de producto y resuelve CPs a códigos SAT de entidad,
     * todo en un único pool de peticiones Guzzle async.
     *
     * @param  string[]  $descriptions
     * @param  string[]  $cpList        Códigos postales únicos a resolver
     * @return array{0: array<string,array>, 1: array<string,string>}
     *         [clasificaciones_por_desc, codigo_estado_por_cp]
     */
    private function classifyAllAtOnce(array $descriptions, array $cpList = []): array
    {
        if (empty($descriptions) && empty($cpList)) {
            return [[], []];
        }

        // Construir textos de catálogos una sola vez
        $catSimple   = require resource_path('views/cfdi/config/sat_catalogo_simple.php');
        $catDetailed = require resource_path('views/cfdi/config/sat_catalogos.php');
        $catEmpaque  = require resource_path('views/cfdi/config/empaque_catalogo.php');
        $catUnidades = require resource_path('views/cfdi/config/sat_unidades.php');

        $txtSimple = '';
        foreach ($catSimple as $item) {
            $txtSimple .= "  {$item['code']}: {$item['description']}\n";
        }

        $txtDetailed = '';
        foreach ($catDetailed as $category => $items) {
            $label        = str_replace('_', ' ', strtoupper($category));
            $txtDetailed .= "[{$label}]\n";
            foreach ($items as $item) {
                $txtDetailed .= "  {$item['code']}: {$item['description']}\n";
            }
        }

        $txtEmpaque = '';
        foreach ($catEmpaque as $item) {
            $txtEmpaque .= "  {$item['code']}: {$item['description']}\n";
        }

        $txtUnidades = '';
        foreach ($catUnidades as $item) {
            $txtUnidades .= "  {$item['code']}: {$item['description']}\n";
        }

        $txtIeps = <<<TXT
  cigarros_enajenados                 → Cigarros, cigarrillos nacionales enajenados en México
  puros_enajenados                    → Puros y tabacos labrados enajenados en México
  puros_mano_enajenados               → Puros y tabacos hechos enteramente a mano enajenados
  bebidas_saborizadas_enajenados      → Refrescos, agua de sabor, bebidas con azúcar (nacionales)
  bebidas_energetizantes_enajenados   → Bebidas energetizantes, shots energéticos (nacionales)
  cigarros_importacion                → Cigarros y cigarrillos importados
  puros_importacion                   → Puros y tabacos labrados importados
  puros_mano_importacion              → Puros a mano importados
  bebidas_saborizadas_importacion     → Bebidas saborizadas importadas
  bebidas_energetizantes_importacion  → Bebidas energetizantes importadas
  alimentos_alta_densidad_enajenados  → Botanas, papas fritas, frituras, Takis, palomitas, nachos, chicharrones, cacahuates, chocolates, dulces, gomitas, helados, galletas, cereales, pasteles (producción nacional)
  bebidas_alcoholicas_enajenados      → Cerveza, tequila, mezcal, vino, ron, whisky, vodka, brandy, aguardiente, sidra (nacionales, enajenadas en México)
  bebidas_alcoholicas_importacion     → Bebidas alcohólicas importadas: whisky, vodka, cognac, ron, vino, cerveza importada
  alimentos_alta_densidad_importacion → Snacks, frituras, chocolates, botanas importadas con alta densidad calórica
  ninguno                             → No aplica ninguna categoría IEPS anterior (servicios, alimentos básicos, medicamentos, etc.)
TXT;

        // ── Preparar lotes (chunks de 50 — mayor tamaño = menos peticiones paralelas) ──
        $descriptions = array_values($descriptions);
        $chunks       = array_chunk($descriptions, 50, false);

        // Parte estática del prompt (catálogos) — construida UNA vez, compartida por todos los chunks
        $staticCatalogs = <<<STATIC
=== CATÁLOGO 1: TIPO DE PRODUCTO (sat_catalogo_simple) ===
{$txtSimple}

=== CATÁLOGO 2: CLAVE GENÉRICA (sat_catalogos — usa el código del sub-item) ===
{$txtDetailed}

=== CATÁLOGO 3: EMPAQUE ===
{$txtEmpaque}

=== CATÁLOGO 4: UNIDAD DE MEDIDA ===
{$txtUnidades}

=== CATÁLOGO 5: IEPS ESPECIAL ===
{$txtIeps}
STATIC;

        // ── Guzzle async: todas las peticiones se lanzan en PARALELO ─────────────
        // En lugar de esperar la respuesta de cada chunk antes de enviar el siguiente,
        // todos los chunks se envían simultáneamente y se espera al más lento.
        // Con 600 descripciones (12 chunks de 50) el tiempo pasa de ~96s a ~8s.
        $apiKey    = config('deepseek.api_key');
        $model     = config('deepseek.model', 'deepseek-chat');
        $baseUri   = 'https://' . ltrim(config('deepseek.base_uri', 'api.deepseek.com/v1'), 'https://') . '/';
        $timeout   = (int) config('deepseek.timeout', 300);

        $guzzle = new \GuzzleHttp\Client([
            'base_uri'        => $baseUri,
            'timeout'         => $timeout,
            'connect_timeout' => 30,
        ]);

        $promises = [];
        foreach ($chunks as $chunkOffset => $chunk) {
            $baseIndex   = $chunkOffset * 50;
            $productList = implode("\n", array_map(
                fn ($i, $desc) => ($baseIndex + $i + 1) . '. ' . $desc,
                array_keys($chunk),
                $chunk
            ));

            $prompt = "Eres un experto en SAT México e IEPS. Clasifica cada producto en los 5 catálogos simultáneamente.\n\n"
                . $staticCatalogs
                . "\n\n=== PRODUCTOS A CLASIFICAR ===\n{$productList}\n\n"
                . "=== INSTRUCCIONES ===\n"
                . "Responde ÚNICAMENTE con un JSON array válido, sin texto ni markdown.\n"
                . "Por cada producto devuelve exactamente este formato:\n"
                . "[{\"index\":1,\"tipo\":\"cód1\",\"generica\":\"cód2\",\"empaque\":\"cód3\",\"unidad\":\"cód4\",\"ieps\":\"clave5_o_ninguno\"}]";

            $promises[$chunkOffset] = $guzzle->postAsync('chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model'       => $model,
                    'messages'    => [['role' => 'user', 'content' => $prompt]],
                    'temperature' => 0.1,
                ],
            ]);
        }

        // ── Petición de CPs en el mismo pool async ────────────────────────────
        $cpList = array_values($cpList);
        if (!empty($cpList)) {
            $catCp    = require resource_path('views/cfdi/config/sat_catalogo_cp.php');
            $txtCp    = '';
            foreach ($catCp as $item) {
                $txtCp .= "  {$item['code']}: {$item['description']}\n";
            }

            $cpNumbered = implode("\n", array_map(
                fn ($i, $cp) => ($i + 1) . '. ' . $cp,
                array_keys($cpList),
                $cpList
            ));

            $cpPrompt = "Eres un experto en códigos postales de México. "
                . "Dado un código postal, determina a qué estado pertenece según el catálogo SAT de entidades federativas.\n\n"
                . "=== CATÁLOGO SAT ENTIDADES FEDERATIVAS ===\n{$txtCp}\n\n"
                . "=== CÓDIGOS POSTALES A RESOLVER ===\n{$cpNumbered}\n\n"
                . "=== INSTRUCCIONES ===\n"
                . "Responde ÚNICAMENTE con un JSON array válido, sin texto ni markdown.\n"
                . "Si el CP no existe o es desconocido usa el código '00'.\n"
                . "Formato exacto:\n"
                . "[{\"index\":1,\"cp\":\"12345\",\"codigo\":\"14\"}]";

            $promises['cp'] = $guzzle->postAsync('chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model'       => $model,
                    'messages'    => [['role' => 'user', 'content' => $cpPrompt]],
                    'temperature' => 0.1,
                ],
            ]);
        }

        // Esperar TODAS las respuestas (settle no lanza excepción si alguna falla)
        $settled = \GuzzleHttp\Promise\Utils::settle($promises)->wait();

        // ── Procesar respuestas de clasificación de productos ─────────────────
        $result = [];
        foreach ($settled as $chunkOffset => $outcome) {
            if ($chunkOffset === 'cp' || $outcome['state'] !== 'fulfilled') {
                continue;
            }

            $body    = json_decode((string) $outcome['value']->getBody(), true);
            $text    = $body['choices'][0]['message']['content'] ?? '';
            $cleaned = preg_replace('/```(?:json)?\s*([\s\S]*?)\s*```/', '$1', trim($text));
            $json    = json_decode($cleaned, true);

            if (!is_array($json)) {
                continue;
            }

            $chunk     = $chunks[$chunkOffset];
            $baseIndex = $chunkOffset * 50;

            foreach ($json as $item) {
                $idx = (int) ($item['index'] ?? 0) - 1 - $baseIndex;
                if (isset($chunk[$idx])) {
                    $result[$chunk[$idx]] = [
                        'tipo'    => $item['tipo']     ?? '000',
                        'generica'=> $item['generica']  ?? '000',
                        'empaque' => $item['empaque']   ?? '000',
                        'unidad'  => $item['unidad']    ?? '000',
                        'ieps'    => $item['ieps']      ?? 'ninguno',
                    ];
                }
            }
        }

        // ── Procesar respuesta de CPs ─────────────────────────────────────────
        $cpCodigos = [];
        if (!empty($cpList) && isset($settled['cp']) && $settled['cp']['state'] === 'fulfilled') {
            $body    = json_decode((string) $settled['cp']['value']->getBody(), true);
            $text    = $body['choices'][0]['message']['content'] ?? '';
            $cleaned = preg_replace('/```(?:json)?\s*([\s\S]*?)\s*```/', '$1', trim($text));
            $json    = json_decode($cleaned, true);

            if (is_array($json)) {
                foreach ($json as $item) {
                    $idx    = (int) ($item['index'] ?? 0) - 1;
                    $codigo = trim((string) ($item['codigo'] ?? '00'));
                    if (isset($cpList[$idx])) {
                        $cpCodigos[$cpList[$idx]] = $codigo !== '' ? $codigo : '00';
                    }
                }
            }
        }

        return [$result, $cpCodigos];
    }

    private function classifyDescriptions(array $descriptions, string $catalogPath): array
    {
        if (empty($descriptions)) {
            return [];
        }

        // Cargar catálogo SAT y construir texto según su formato
        $catalog     = require resource_path($catalogPath);
        $catalogText = '';

        // Detectar si es formato plano [['code','description'],...] o anidado ['categoria' => [...]]
        $firstKey = array_key_first($catalog);
        if (is_int($firstKey)) {
            // Formato plano (sat_catalogo_simple.php)
            foreach ($catalog as $item) {
                $catalogText .= "  Código {$item['code']}: {$item['description']}\n";
            }
        } else {
            // Formato anidado por categoría (sat_catalogos.php)
            foreach ($catalog as $category => $items) {
                $label        = str_replace('_', ' ', strtoupper($category));
                $catalogText .= "\n[{$label}]\n";
                foreach ($items as $item) {
                    $catalogText .= "  Código {$item['code']}: {$item['description']}\n";
                }
            }
        }

        // Lista numerada de descripciones
        $descriptions = array_values($descriptions);
        $numbered     = array_map(
            fn ($i, $desc) => ($i + 1) . '. ' . $desc,
            array_keys($descriptions),
            $descriptions
        );
        $productList = implode("\n", $numbered);

        $prompt = <<<PROMPT
Eres un experto en el catálogo SAT de México para el Impuesto Especial sobre Producción y Servicios (IEPS).

Tu tarea es clasificar cada producto en la categoría y código correcto del siguiente catálogo SAT.

=== CATÁLOGO SAT IEPS ===
{$catalogText}

=== PRODUCTOS A CLASIFICAR ===
{$productList}

=== INSTRUCCIONES ===
- Analiza la descripción de cada producto y determina a qué categoría y código pertenece.
- Si el producto NO pertenece a ninguna categoría del catálogo, usa: categoria "sin_clasificar", codigo "000", tipo "Sin clasificar".
- Responde ÚNICAMENTE con un JSON array válido, sin texto adicional, sin bloques de código markdown.
- Formato exacto de respuesta:
[
  {"index": 1, "categoria": "nombre_categoria", "codigo": "XXX", "tipo": "Nombre exacto del tipo del catálogo"},
  {"index": 2, ...}
]
PROMPT;

        try {
            /** @var DeepSeekService $deepseek */
            $deepseek = app(DeepSeekService::class);
            $response = $deepseek->chat($prompt, ['temperature' => 0.1]);

            // Limpiar posibles bloques markdown que el modelo pueda incluir
            $cleaned = preg_replace('/```(?:json)?\s*([\s\S]*?)\s*```/', '$1', trim($response));

            $json = json_decode($cleaned, true);

            $result = [];
            if (is_array($json)) {
                foreach ($json as $item) {
                    $idx = (int) ($item['index'] ?? 0) - 1;
                    if (isset($descriptions[$idx])) {
                        $categoria = $item['categoria'] ?? 'sin_clasificar';
                        $codigo    = $item['codigo']    ?? '000';
                        $tipo      = $item['tipo']      ?? 'Sin clasificar';

                        $result[$descriptions[$idx]] = $codigo;
                    }
                }
            }

            return $result;

        } catch (\Throwable $e) {
            // Si falla DeepSeek, las filas quedarán como "Sin clasificar"
            return [];
        }
    }

    /**
     * Clasifica descripciones en una de las 10 categorías especiales de IEPS
     * (cuotas específicas por tabaco/bebidas, enajenación vs importación).
     * Devuelve la clave interna del tipo que le corresponde, o "ninguno".
     *
     * @param  string[]  $descriptions
     * @return array<string, string>  Mapa [descripcion => clave_interna]
     */
    private function classifyIepsEspeciales(array $descriptions): array
    {
        if (empty($descriptions)) {
            return [];
        }

        $descriptions = array_values($descriptions);
        $numbered     = array_map(
            fn ($i, $desc) => ($i + 1) . '. ' . $desc,
            array_keys($descriptions),
            $descriptions
        );
        $productList = implode("\n", $numbered);

        $prompt = <<<PROMPT
Eres un experto en el sistema IEPS (Impuesto Especial sobre Producción y Servicios) de México.

Analiza cada descripción de producto y determina a cuál de las siguientes categorías de IEPS pertenece.
Considera si el producto es nacional (enajenado) o importado según el contexto de la descripción.

=== CATEGORÍAS DISPONIBLES (usa exactamente estas claves) ===
cigarros_enajenados                  → Cigarros, cigarrillos, tabacos en cigarrillo para consumo nacional
puros_enajenados                     → Puros y otros tabacos labrados enajenados en México
puros_mano_enajenados                → Puros y tabacos labrados hechos enteramente a mano enajenados
bebidas_saborizadas_enajenados       → Bebidas saborizadas, refrescos, agua de sabor con azúcares añadidos (nacionales)
bebidas_energetizantes_enajenados    → Bebidas energetizantes, shots energéticos (nacionales)
cigarros_importacion                 → Cigarros importados
puros_importacion                    → Puros y tabacos labrados importados
puros_mano_importacion               → Puros y tabacos a mano importados
bebidas_saborizadas_importacion      → Bebidas saborizadas importadas
bebidas_energetizantes_importacion   → Bebidas energetizantes importadas
alimentos_alta_densidad_enajenados   → Alimentos no básicos con alta densidad calórica: botanas, papas fritas, frituras, takis, palomitas, nachos, chocolates, dulces, gomitas, helados, galletas, cereales, pasteles, chicharrones, cacahuates (nacionales)
bebidas_alcoholicas_enajenados       → Bebidas alcohólicas nacionales: cerveza, tequila, mezcal, vino, ron, whisky, vodka, brandy, aguardiente, sidra (enajenadas en México)
bebidas_alcoholicas_importacion      → Bebidas alcohólicas importadas: whisky, vodka, cognac, ron, vino importado
alimentos_alta_densidad_importacion  → Alimentos no básicos con alta densidad calórica importados: snacks, frituras, chocolates importados
ninguno                              → El producto NO pertenece a ninguna de las categorías anteriores (servicios, medicamentos, alimentos básicos, etc.)

=== PRODUCTOS A CLASIFICAR ===
{$productList}

=== INSTRUCCIONES ===
- Responde ÚNICAMENTE con un JSON array válido, sin texto adicional, sin bloques markdown.
- Formato exacto:
[
  {"index": 1, "clave": "clave_exacta_de_la_lista"},
  {"index": 2, "clave": "ninguno"}
]
PROMPT;

        try {
            /** @var DeepSeekService $deepseek */
            $deepseek = app(DeepSeekService::class);
            $response = $deepseek->chat($prompt, ['temperature' => 0.1]);

            $cleaned = preg_replace('/```(?:json)?\s*([\s\S]*?)\s*```/', '$1', trim($response));
            $json    = json_decode($cleaned, true);

            $result = [];
            if (is_array($json)) {
                foreach ($json as $item) {
                    $idx   = (int) ($item['index'] ?? 0) - 1;
                    $clave = $item['clave'] ?? 'ninguno';
                    if (isset($descriptions[$idx])) {
                        $result[$descriptions[$idx]] = $clave;
                    }
                }
            }

            return $result;

        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Claves SAT de unidad (UN/ECE Rec 20) que identifican un tipo de empaque,
     * mapeadas a los códigos de resources/views/cfdi/config/empaque_catalogo.php.
     */
    private const CLAVE_UNIDAD_EMPAQUE = [
        'XBA' => '001', // Barrica / Barrel
        'XBG' => '002', // Bolsa / Bag
        'XSA' => '002', // Saco → bolsa
        'XCI' => '003', // Bote / Canister
        'XCA' => '003', // Lata-bote
        'XBO' => '004', // Botella
        'XBT' => '004', // Botella
        'XBX' => '005', // Caja
        'XCT' => '005', // Cartón / Caja
        'XPK' => '005', // Paquete → caja
        'XPA' => '006', // Cajetilla / Packet
        'XBJ' => '012', // Cubeta / Bucket → otro contenedor
        'XCS' => '008', // Estuche / Case
        'XCY' => '009', // Garrafón / Cylinder
        'XCN' => '010', // Lata / Can
        'XBK' => '011', // Mazo / Basket
        'XCH' => '012', // Otro contenedor
    ];

    /**
     * Palabras del texto libre de `Unidad` que identifican un empaque.
     * Se evalúan sobre el atributo Unidad del concepto (ej. "CUBETA 19/LT").
     */
    private const UNIDAD_TEXTO_EMPAQUE = [
        'barrica'   => '001',
        'barril'    => '001',
        'tambo'     => '001',
        'bolsa'     => '002',
        'saco'      => '002',
        'bulto'     => '002', // "BT50"/"BT25" = bulto de cemento/cal → bolsa
        'costal'    => '007',
        // 'botella' antes que 'bote' — 'bote' es subcadena de 'botella'
        'botella'   => '004',
        'bote'      => '003',
        'frasco'    => '004',
        // 'cajetilla' antes que 'caja' — 'caja' es subcadena de 'cajetilla'
        'cajetilla' => '006',
        'caja'      => '005',
        'estuche'   => '008',
        'garrafon'  => '009',
        'garrafón'  => '009',
        'garrafa'   => '009',
        'lata'      => '010',
        'mazo'      => '011',
        'cubeta'    => '012',
        'galon'     => '012',
        'galón'     => '012',
        'tanque'    => '012',
        'cuñete'    => '012',
        'cunete'    => '012',
    ];

    /**
     * Resuelve la columna Empaque a partir de los datos reales del XML.
     *
     * Prioridad:
     *   1. ClaveUnidad (código SAT normalizado — dato más confiable del XML)
     *   2. Texto libre del atributo Unidad (ej. "CUBETA 19/LT" → 012)
     *   3. Clasificación IA sobre la descripción (respaldo)
     *   4. '000' — Sin selección
     */
    private function resolveEmpaque(string $claveUnidad, string $unidad, string $fallback = '000'): string
    {
        $clave = strtoupper(trim($claveUnidad));
        if ($clave !== '' && isset(self::CLAVE_UNIDAD_EMPAQUE[$clave])) {
            return self::CLAVE_UNIDAD_EMPAQUE[$clave];
        }

        $texto = mb_strtolower(trim($unidad));
        if ($texto !== '') {
            // "BT50" / "BT 25" = bulto (cemento, cal) → bolsa
            if (preg_match('/^bt\s*\d/i', $texto)) {
                return '002';
            }

            foreach (self::UNIDAD_TEXTO_EMPAQUE as $palabra => $codigo) {
                if (str_contains($texto, $palabra)) {
                    return $codigo;
                }
            }
        }

        // Respaldo: lo que haya dicho la IA sobre la descripción
        return $fallback !== '' ? $fallback : '000';
    }

    /**
     * Extrae la presentación (contenido por empaque) desde el XML.
     * Busca una cifra con unidad de volumen/peso en el atributo Unidad y,
     * si no la encuentra, en la Descripción del concepto.
     * Ej. "CUBETA 19/LT" → 19.0 | "PRO 1000 PLUS NF V1 CONT 19.00 L" → 19.0
     *
     * @return float  0.0 cuando el XML no declara presentación
     */
    private function resolvePresentacion(string $unidad, string $descripcion): float
    {
        // ml/mL/L/lt/litro(s), g/gr/gramos, kg/kilo(s) — con separador opcional
        $patron = '/(\d+(?:[.,]\d+)?)\s*\/?\s*'
            . '(ml|mililitros?|l|lt|lts|litros?|g|gr|gramos?|kg|kgs|kilos?|kilogramos?)\b/iu';

        foreach ([$unidad, $descripcion] as $fuente) {
            $fuente = trim($fuente);
            if ($fuente === '') {
                continue;
            }

            if (preg_match($patron, $fuente, $m)) {
                return (float) str_replace(',', '.', $m[1]);
            }
        }

        return 0.0;
    }

    /**
     * Dado un valor flotante de TasaOCuota, devuelve la clave del array de tasas
     * conocidas que más se le acerca.
     * Esto resuelve variaciones de precisión decimal en el XML
     * (ej. "0.53" vs "0.530000", o "0.5300001").
     *
     * @param  float    $value
     * @param  string[] $keys   Claves del array $iepsColumns
     * @return string|null
     */
    private function findNearestIepsKey(float $value, array $keys): ?string
    {
        $nearest = null;
        $minDiff = PHP_FLOAT_MAX;

        foreach ($keys as $key) {
            $diff = abs($value - (float) $key);
            if ($diff < $minDiff) {
                $minDiff = $diff;
                $nearest = $key;
            }
        }

        return $nearest;
    }

    public function removeXml(int $index): void
    {
        if (isset($this->xmls[$index])) {
            // Eliminar el archivo físico del disco al quitar de la lista
            Storage::disk('local')->delete($this->xmls[$index]['path']);
        }
        unset($this->xmls[$index]);
        $this->xmls = array_values($this->xmls);
    }

    public function render()
    {
        return view('livewire.cfdi-excel-converter');
    }
}
