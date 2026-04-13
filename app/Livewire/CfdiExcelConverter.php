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
            'IEPS pagados en la importación por la aplicación de la cuota específica de cigarros',
            'IEPS pagados en la importación por la aplicación de la cuota específica de los puros y otros tabacos labrados',
            'IEPS pagados en la importación por la aplicación de la cuota específica de los puros y otros tabacos labrados hechos enteramente a mano',
            'IEPS pagado en la importación por la aplicación de la cuota de bebidas saborizadas',
            'IEPS pagado en la importación por la aplicación de la cuota de bebidas energetizantes',
            'IEPS trasladado por la aplicación de la tasa a alimentos no básicos con alta densidad calórica enajenados',
            'IEPS trasladado por la aplicación de la tasa a bebidas alcohólicas enajenadas',
            'IEPS trasladado por la aplicación de la tasa a bebidas alcohólicas importadas',
            'IEPS pagado en la importación por la aplicación de la tasa a alimentos no básicos con alta densidad calórica',
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
                // Condiciones: no es pago, está gravado, tiene IEPS real > 0
                $necesitaIA = $tipoComprobante !== 'P'
                    && $objetoImp !== '01'
                    && $importeIeps !== ''
                    && (float) $importeIeps > 0;

                if ($necesitaIA) {
                    $uniqueDescriptions[$descripcion] = true;
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
                    'iepsColumns' => array_values($iepsColumns),
                    'baseIva'     => $baseIva,
                    'importeIeps' => $importeIeps,
                    'importeIva'  => $importeIva,
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

        // ── Paso 2: clasificar todas las descripciones en UNA sola llamada a DeepSeek ──
        $descriptions   = array_keys($uniqueDescriptions);
        $allClassifications = $this->classifyAllAtOnce($descriptions);

        // ── Paso 3: escribir las filas en el Excel ───────────────────────────────
        $iepsEspKeys = [
            'cigarros_enajenados',
            'puros_enajenados',
            'puros_mano_enajenados',
            'bebidas_saborizadas_enajenados',
            'bebidas_energetizantes_enajenados',
            'cigarros_importacion',
            'puros_importacion',
            'puros_mano_importacion',
            'bebidas_saborizadas_importacion',
            'bebidas_energetizantes_importacion',
            'alimentos_alta_densidad_enajenados',
            'bebidas_alcoholicas_enajenados',
            'bebidas_alcoholicas_importacion',
            'alimentos_alta_densidad_importacion',
        ];

        // Array de filas: se acumulan todas y se escriben en UNA sola llamada a PhpSpreadsheet
        $excelRows    = [];
        $emptyEsp14   = array_fill(0, 14, ''); // constante reutilizable (evita array_fill por fila)

        foreach ($pendingRows as $entry) {
            // Si el concepto no necesitaba IA, las columnas de clasificación van vacías
            if ($entry['necesitaIA']) {
                $cls           = $allClassifications[$entry['descripcion']] ?? [];
                $tipoProd      = $cls['tipo']     ?? '000';
                $claveGenerica = $cls['generica'] ?? '000';
                $empaque       = $cls['empaque']  ?? '000';
                $unidadMedida  = $cls['unidad']   ?? '000';
                $iepsEspCat    = $cls['ieps']     ?? 'ninguno';
            } else {
                $tipoProd = $claveGenerica = $empaque = $unidadMedida = '';
                $iepsEspCat = 'ninguno';
            }

            // Distribuir el importe IEPS en la columna especial que corresponda (14 columnas)
            $iepsEspCols = $emptyEsp14;
            $iepsEspIdx  = array_search($iepsEspCat, $iepsEspKeys, true);
            if ($iepsEspIdx !== false && $entry['importeIeps'] !== '') {
                $iepsEspCols[$iepsEspIdx] = $entry['importeIeps'];
            }

            // ── Fila en el orden solicitado ───────────────────────────────────
            $excelRows[] = array_merge(
                [
                    '',                    // 1  Tipo de operación (vacía)
                    $entry['rfcEmisor'],   // 2  RFC Emisor
                    $entry['nomEmisor'],   // 3  Nombre Emisor
                    $entry['rfcReceptor'], // 4  RFC Receptor
                    $entry['nomReceptor'], // 5  Nombre Receptor
                    $entry['domFiscal'],        // 6  Clave entidad federativa
                    $entry['lugarExpedicion'],  // 7  Lugar de Expedición
                    $tipoProd,                  // 8  Tipo de Producto
                    '',                    // 8  Empresa tabacalera (vacía)
                    $claveGenerica,        // 9  Clave genérica del producto
                ],
                $entry['iepsColumns'],     // 10-19  IEPS 3% … 160% (10 columnas)
                [
                    $entry['cantidad'],    // 20  Volumen total de enajenación
                    $empaque,              // 21  Empaque
                    '',                    // 22  Presentación (vacía)
                    $unidadMedida,         // 23  Unidad de Medida
                    $entry['baseIva'],     // 24  Valor de la operación (antes de impuestos)
                    '',                    // 25  Valor valuada precio detallista (vacía)
                    $entry['importeIeps'], // 26  IEPS Pagado / Trasladado
                    $entry['importeIva'],  // 27  IVA Pagado / Trasladado
                ],
                $iepsEspCols               // 28-41  IEPS específicos (14 columnas)
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

        // Esperar TODAS las respuestas (settle no lanza excepción si alguna falla)
        $settled = \GuzzleHttp\Promise\Utils::settle($promises)->wait();

        // ── Procesar respuestas ───────────────────────────────────────────────────
        $result = [];
        foreach ($settled as $chunkOffset => $outcome) {
            if ($outcome['state'] !== 'fulfilled') {
                continue; // el chunk falló — sus productos quedan con defaults
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
