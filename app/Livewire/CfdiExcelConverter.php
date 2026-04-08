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

    public function export(): void
    {
        $this->processing = true;
        $this->completed  = false;
        $startTime        = now();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        // ── Cabeceras ────────────────────────────────────────────────────────────
        $headers = [
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
            'Descripción',
            'Volumen total de enajenación por tipo de producto',
            'Tipo de Producto',
            'Clave generica del producto',
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
            'Empaque',
            'Unidad de Medida',
            'Valor de la operación (antes de impuestos)',
            'IEPS Pagado / Trasladado',
            'IVA Pagado / Trasladado',
            'IEPS trasladado por la aplicación de la cuota específica de cigarros enajenados',
            'IEPS trasladado por la aplicación de la cuota específica por el peso total en gramos de los puros y otros tabacos labrados enajenados',
            'IEPS trasladado por la aplicación de la cuota específica por el peso total en gramos de los puros y otros tabacos labrados hechos enteramente a mano enajenados',
            'IEPS trasladado por la aplicación de la cuota de bebidas saborizadas',
            'IEPS trasladado por la aplicación de la cuota de bebidas energetizantes',
            'IEPS pagados en la importación por la aplicación de la cuota específica de cigarros',
            'IEPS pagados en la importación por la aplicación de la cuota específica de los puros y otros tabacos labrados',
            'IEPS pagados en la importación por la aplicación de la cuota específica de los puros y otros tabacos labrados hechos enteramente a mano',
            'IEPS pagado en la importación por la aplicación de la cuota de bebidas saborizadas',
            'IEPS pagado en la importación por la aplicación de la cuota de bebidas energetizantes',
            'IEPS trasladado por la aplicación de la tasa a alimentos no básicos con alta densidad calórica enajenados',
            'IEPS trasladado por la aplicación de la tasa a bebidas alcohólicas enajenadas',
            'IEPS trasladado por la aplicación de la tasa a bebidas alcohólicas importadas',
            'IEPS pagado en la importación por la aplicación de la tasa a alimentos no básicos con alta densidad calórica',
            // Campos adicionales
            'RegimenFiscalReceptor',
            'Clave de la entidad Federativa',
            'IEPS Retenido',
            'Complementos comprobante',
            'Complementos concepto',
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
        $pendingRows          = [];   // todas las filas a escribir
        $uniqueDescriptions   = [];   // descripciones únicas para clasificar

        foreach ($this->xmls as $xml) {

            $xmlContent = simplexml_load_string($xml->get());
            $namespaces = $xmlContent->getNamespaces(true);

            $xmlContent->registerXPathNamespace('cfdi', $namespaces['cfdi'] ?? 'http://www.sat.gob.mx/cfd/4');
            $xmlContent->registerXPathNamespace('tfd',  $namespaces['tfd']  ?? 'http://www.sat.gob.mx/TimbreFiscalDigital');

            $comprobante = $xmlContent->attributes();
            $emisor      = $xmlContent->xpath('//cfdi:Emisor')[0]?->attributes()   ?? [];
            $receptor    = $xmlContent->xpath('//cfdi:Receptor')[0]?->attributes() ?? [];

            // UUID y fecha timbrado
            $uuid          = '';
            $fechaTimbrado = '';
            $tfd = $xmlContent->xpath('//cfdi:Complemento/tfd:TimbreFiscalDigital')[0] ?? null;
            if ($tfd) {
                $attrs         = $tfd->attributes();
                $uuid          = (string) ($attrs['UUID']          ?? '');
                $fechaTimbrado = (string) ($attrs['FechaTimbrado'] ?? '');
            }

            // Tipo relación
            $tipoRelacion = '';
            $relacionado  = $xmlContent->xpath('//cfdi:CfdiRelacionados')[0] ?? null;
            if ($relacionado) {
                $tipoRelacion = (string) ($relacionado->attributes()['TipoRelacion'] ?? '');
            }

            // Complementos del comprobante
            $complementos = [];
            foreach ($xmlContent->xpath('//cfdi:Complemento/*') as $comp) {
                $complementos[] = $comp->getName();
            }

            // Impuestos locales
            $totalLocalTrasladado = '0';
            $totalLocalRetenido   = '0';
            $ish                  = '0';
            foreach ($xmlContent->xpath('//cfdi:Complemento/*') as $comp) {
                if (stripos($comp->getName(), 'ImpuestosLocales') !== false) {
                    $attrs                = $comp->attributes();
                    $totalLocalTrasladado = (string) ($attrs['TotaldeTraslados']   ?? '0');
                    $totalLocalRetenido   = (string) ($attrs['TotaldeRetenciones'] ?? '0');
                    foreach ($comp->children() as $child) {
                        if ($child->getName() === 'TrasladosLocales') {
                            $childAttrs = $child->attributes();
                            if (isset($childAttrs['ImpLocTrasladado']) &&
                                strtoupper((string) $childAttrs['ImpLocTrasladado']) === 'ISH') {
                                $ish = (string) ($childAttrs['Importe'] ?? '0');
                            }
                        }
                    }
                }
            }

            // Impuestos globales del comprobante
            $trasladados = '0';
            $retenidos   = '0';
            $iva16       = '';
            $isrRet      = '';
            $ivaRet      = '';
            $iepsRet     = '';
            $totalIEPS   = '0';

            $impuestos = $xmlContent->children($namespaces['cfdi'])->Impuestos ?? null;
            if ($impuestos) {
                $impAttrs    = $impuestos->attributes();
                $trasladados = (string) ($impAttrs['TotalImpuestosTrasladados'] ?? '0');
                $retenidos   = (string) ($impAttrs['TotalImpuestosRetenidos']   ?? '0');

                foreach ($impuestos->Retenciones->Retencion ?? [] as $ret) {
                    $attrs  = $ret->attributes();
                    $imp    = (string) $attrs['Impuesto'];
                    $impVal = (string) $attrs['Importe'];
                    if ($imp === '001') $isrRet  = $impVal;
                    if ($imp === '002') $ivaRet  = $impVal;
                    if ($imp === '003') $iepsRet = $impVal;
                }

                foreach ($impuestos->Traslados->Traslado ?? [] as $tra) {
                    $attrs    = $tra->attributes();
                    $impuesto = (string) ($attrs['Impuesto']    ?? '');
                    $importe  = (string) ($attrs['Importe']     ?? '');

                    if ($impuesto === '002' && (string) ($attrs['TasaOCuota'] ?? '') === '0.160000') {
                        $iva16 = $importe;
                    }
                    if ($impuesto === '003') {
                        $totalIEPS = $importe;
                    }
                }
            }

            // Datos comunes del comprobante (se repiten en cada fila de concepto)
            $cfdiBase = [
                $tipoRelacion,
                (string) ($comprobante['TipoDeComprobante']   ?? ''),
                (string) ($comprobante['Fecha']               ?? ''),
                $fechaTimbrado,
                (string) ($comprobante['Serie']               ?? ''),
                (string) ($comprobante['Folio']               ?? ''),
                $uuid,
                (string) ($emisor['Rfc']                      ?? ''),
                (string) ($emisor['Nombre']                   ?? ''),
                (string) ($receptor['Rfc']                    ?? ''),
                (string) ($receptor['Nombre']                 ?? ''),
                (string) ($receptor['UsoCFDI']                ?? ''),
                (string) ($comprobante['SubTotal']            ?? ''),
                (string) ($comprobante['Descuento']           ?? ''),
                $totalIEPS,
                $iva16,
                $ivaRet,
                $isrRet,
                $ish,
                (string) ($comprobante['Total']               ?? ''),
                $trasladados,
                $retenidos,
                $totalLocalTrasladado,
                $totalLocalRetenido,
                $this->formasDePago[(string) ($comprobante['FormaPago'] ?? '')] ?? (string) ($comprobante['FormaPago'] ?? ''),
                (string) ($comprobante['MetodoPago']          ?? ''),
            ];

            // Iterar concepto a concepto → una fila por producto
            $todasDescripciones = [];
            foreach ($xmlContent->xpath('//cfdi:Conceptos/cfdi:Concepto') as $concepto) {
                $todasDescripciones[] = (string) ($concepto->attributes()['Descripcion'] ?? '');
            }
            $conceptosJoined = implode(' | ', $todasDescripciones);

            foreach ($xmlContent->xpath('//cfdi:Conceptos/cfdi:Concepto') as $concepto) {
                $attrs       = $concepto->attributes();
                $descripcion = (string) ($attrs['Descripcion'] ?? '');

                // Registrar descripción única para clasificar después
                $uniqueDescriptions[$descripcion] = true;

                // ── Columnas IEPS por tasa ────────────────────────────────────────
                // El orden debe coincidir exactamente con los headers del Excel
                $iepsColumns = [
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

                $cfdiNs = $namespaces['cfdi'] ?? 'http://www.sat.gob.mx/cfd/4';
                $impuestosConcepto = $concepto->children($cfdiNs)->Impuestos ?? null;
                $baseIva      = '';
                $importeIeps  = '';
                $importeIva   = '';
                if ($impuestosConcepto) {
                    $traslados = iterator_to_array($impuestosConcepto->Traslados->Traslado ?? [], false);
                    foreach ($traslados as $traslado) {
                        $tAttrs   = $traslado->attributes();
                        $impuesto = (string) ($tAttrs['Impuesto']   ?? '');
                        $tasa     = (string) ($tAttrs['TasaOCuota'] ?? '');
                        $importe  = (string) ($tAttrs['Importe']    ?? '');

                        if ($impuesto === '003') {
                            // IEPS → columna por tasa + total pagado
                            if ($tasa !== '') {
                                $nearestKey = $this->findNearestIepsKey((float) $tasa, array_keys($iepsColumns));
                                if ($nearestKey !== null) {
                                    $iepsColumns[$nearestKey] = $importe;
                                }
                            }
                            $importeIeps = $importe;
                        }

                        if ($impuesto === '002') {
                            // IVA → base antes de impuestos + total pagado
                            $baseIva    = (string) ($tAttrs['Base'] ?? '');
                            $importeIva = $importe;
                        }
                    }
                }

                $pendingRows[] = [
                    'base'        => $cfdiBase,
                    'descripcion' => $descripcion,
                    'conceptos'   => $conceptosJoined,
                    'cantidad'    => (string) ($attrs['Cantidad'] ?? ''),
                    'iepsColumns' => array_values($iepsColumns),
                    'baseIva'     => $baseIva,
                    'importeIeps' => $importeIeps,
                    'importeIva'  => $importeIva,
                    'regimenRec'  => (string) ($receptor['RegimenFiscalReceptor']   ?? ''),
                    'domFiscal'   => (string) ($receptor['DomicilioFiscalReceptor'] ?? ''),
                    'iepsRet'     => $iepsRet,
                    'complementos'=> implode(' | ', array_unique($complementos)),
                ];
            }
        }

        // ── Paso 2: clasificar todas las descripciones en UNA sola llamada a DeepSeek ──
        $descriptions   = array_keys($uniqueDescriptions);
        $allClassifications = $this->classifyAllAtOnce($descriptions);

        // ── Paso 3: escribir las filas en el Excel ───────────────────────────────
        $iepsEspKeys = [
            'cigarros_enajenados', 'puros_enajenados', 'puros_mano_enajenados',
            'bebidas_saborizadas_enajenados', 'bebidas_energetizantes_enajenados',
            'cigarros_importacion', 'puros_importacion', 'puros_mano_importacion',
            'bebidas_saborizadas_importacion', 'bebidas_energetizantes_importacion',
            'alimentos_alta_densidad_enajenados', 'bebidas_alcoholicas_enajenados',
            'bebidas_alcoholicas_importacion', 'alimentos_alta_densidad_importacion',
        ];

        $row = 2;
        foreach ($pendingRows as $entry) {
            $cls           = $allClassifications[$entry['descripcion']] ?? [];
            $tipoProd      = $cls['tipo']    ?? '000';
            $claveGenerica = $cls['generica'] ?? '000';
            $empaque       = $cls['empaque']  ?? '000';
            $unidadMedida  = $cls['unidad']   ?? '000';

            // Distribuir el importe IEPS en la columna especial que corresponda
            $iepsEspCols = array_fill(0, 14, '');
            $iepsEspCat  = $cls['ieps'] ?? 'ninguno';
            $iepsEspIdx  = array_search($iepsEspCat, $iepsEspKeys, true);
            if ($iepsEspIdx !== false && $entry['importeIeps'] !== '') {
                $iepsEspCols[$iepsEspIdx] = $entry['importeIeps'];
            }

            $sheet->fromArray(array_merge(
                $entry['base'],
                [
                    $entry['conceptos'],
                    $entry['descripcion'],
                    $entry['cantidad'],
                    $tipoProd,
                    $claveGenerica,
                ],
                $entry['iepsColumns'],
                [$empaque, $unidadMedida, $entry['baseIva'], $entry['importeIeps'], $entry['importeIva']],
                $iepsEspCols,
                [
                    $entry['regimenRec'],
                    $entry['domFiscal'],
                    $entry['iepsRet'],
                    $entry['complementos'],
                ]
            ), null, 'A' . $row);

            $row++;
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
     * Clasifica todos los campos en una sola llamada a DeepSeek por lote.
     * Devuelve: [descripcion => ['tipo'=>'','generica'=>'','empaque'=>'','unidad'=>'','ieps'=>'']]
     *
     * @param  string[]  $descriptions
     * @return array<string, array>
     */
    private function classifyAllAtOnce(array $descriptions): array
    {
        if (empty($descriptions)) {
            return [];
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
  cigarros_enajenados                 → Cigarros, cigarrillos nacionales
  puros_enajenados                    → Puros y tabacos labrados enajenados
  puros_mano_enajenados               → Puros a mano enajenados
  bebidas_saborizadas_enajenados      → Refrescos, agua de sabor con azúcar (nacionales)
  bebidas_energetizantes_enajenados   → Bebidas energetizantes nacionales
  cigarros_importacion                → Cigarros importados
  puros_importacion                   → Puros importados
  puros_mano_importacion              → Puros a mano importados
  bebidas_saborizadas_importacion     → Bebidas saborizadas importadas
  bebidas_energetizantes_importacion  → Bebidas energetizantes importadas
  alimentos_alta_densidad_enajenados  → Botanas, papas fritas, frituras, chocolates, dulces, helados, cereales, galletas (nacionales)
  bebidas_alcoholicas_enajenados      → Cerveza, tequila, mezcal, vino, ron, whisky, vodka, brandy (nacionales)
  bebidas_alcoholicas_importacion     → Bebidas alcohólicas importadas
  alimentos_alta_densidad_importacion → Snacks, botanas, chocolates importados
  ninguno                             → No aplica ninguna categoría IEPS anterior
TXT;

        // Procesar en lotes de 30
        $descriptions = array_values($descriptions);
        $chunks       = array_chunk($descriptions, 30, false);
        $result       = [];

        /** @var DeepSeekService $deepseek */
        $deepseek = app(DeepSeekService::class);

        foreach ($chunks as $chunkOffset => $chunk) {
            $baseIndex   = $chunkOffset * 30;
            $numbered    = array_map(
                fn ($i, $desc) => ($baseIndex + $i + 1) . '. ' . $desc,
                array_keys($chunk),
                $chunk
            );
            $productList = implode("\n", $numbered);

            $prompt = <<<PROMPT
Eres un experto en SAT México e IEPS. Clasifica cada producto en los 5 catálogos simultáneamente.

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

=== PRODUCTOS A CLASIFICAR ===
{$productList}

=== INSTRUCCIONES ===
Responde ÚNICAMENTE con un JSON array válido, sin texto ni markdown.
Por cada producto devuelve exactamente este formato:
[
  {
    "index": 1,
    "tipo": "código de catálogo 1",
    "generica": "código de catálogo 2",
    "empaque": "código de catálogo 3",
    "unidad": "código de catálogo 4",
    "ieps": "clave_exacta de catálogo 5 o ninguno"
  }
]
PROMPT;

            try {
                $response = $deepseek->chat($prompt, ['temperature' => 0.1]);
                $cleaned  = preg_replace('/```(?:json)?\s*([\s\S]*?)\s*```/', '$1', trim($response));
                $json     = json_decode($cleaned, true);

                if (is_array($json)) {
                    foreach ($json as $item) {
                        $idx = (int) ($item['index'] ?? 0) - 1 - $baseIndex;
                        if (isset($chunk[$idx])) {
                            $result[$chunk[$idx]] = [
                                'tipo'    => $item['tipo']    ?? '000',
                                'generica'=> $item['generica'] ?? '000',
                                'empaque' => $item['empaque']  ?? '000',
                                'unidad'  => $item['unidad']   ?? '000',
                                'ieps'    => $item['ieps']     ?? 'ninguno',
                            ];
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Si falla un lote, ese lote queda con defaults
            }
        }

        return $result;
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
        unset($this->xmls[$index]);
        $this->xmls = array_values($this->xmls);
    }

    public function render()
    {
        return view('livewire.cfdi-excel-converter');
    }
}
