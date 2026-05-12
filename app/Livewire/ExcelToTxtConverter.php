<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelToTxtConverter extends Component
{
    use WithFileUploads;

    /** Archivo Excel subido por el usuario */
    public $excel;

    /** Nombre original del Excel */
    public string $fileName = '';

    /** URL pública del TXT generado (para descargar) */
    public string $downloadLink = '';

    /** Nombre sugerido del TXT */
    public string $txtName = '';

    /** Cantidad de filas convertidas */
    public int $rowCount = 0;

    /** Bandera de proceso completado */
    public bool $completed = false;

    /**
     * Se dispara automáticamente al subir el Excel.
     * Convierte el archivo a TXT con pipes y deja un link de descarga.
     */
    public function updatedExcel(): void
    {
        if (!$this->excel || !$this->excel->isValid()) {
            return;
        }

        $extension = strtolower($this->excel->getClientOriginalExtension());
        if (!in_array($extension, ['xlsx', 'xls', 'xlsm'], true)) {
            $this->addError('excel', 'Solo se aceptan archivos .xlsx, .xls o .xlsm');
            return;
        }

        $this->fileName = $this->excel->getClientOriginalName();

        // Guardar Excel temporalmente
        $tmpPath = $this->excel->store('excel_tmp', 'local');
        $absTmp  = Storage::disk('local')->path($tmpPath);

        // Leer Excel
        $spreadsheet = IOFactory::load($absTmp);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray(null, true, true, false); // sin formula calc shortcut, valores

        // Construir líneas TXT separadas por pipes
        // - Omite filas completamente vacías
        // - Omite la primera fila si parece cabecera (todas las celdas son texto no-numérico)
        $lines = [];
        $isFirst = true;
        foreach ($rows as $row) {
            // Saltar filas totalmente vacías
            $hasData = false;
            foreach ($row as $cell) {
                if ($cell !== null && trim((string) $cell) !== '') {
                    $hasData = true;
                    break;
                }
            }
            if (!$hasData) {
                continue;
            }

            // Detectar cabecera en la primera fila válida (todas las celdas no numéricas)
            if ($isFirst) {
                $isFirst = false;
                $looksLikeHeader = true;
                foreach ($row as $cell) {
                    $v = trim((string) $cell);
                    if ($v === '') continue;
                    if (is_numeric($v)) { $looksLikeHeader = false; break; }
                }
                if ($looksLikeHeader) {
                    continue; // saltar cabecera
                }
            }

            // Sanitizar cada celda (evitar pipes/saltos internos)
            $clean = array_map(function ($cell) {
                $s = (string) ($cell ?? '');
                $s = str_replace(['|', "\r", "\n"], ['/', ' ', ' '], $s);
                return trim($s);
            }, $row);

            $lines[] = implode('|', $clean);
        }

        $this->rowCount = count($lines);
        $content        = implode("\r\n", $lines) . "\r\n";

        // Nombre del archivo TXT
        $base          = pathinfo($this->fileName, PATHINFO_FILENAME);
        $suffix        = substr(Str::uuid(), -6);
        $this->txtName = $base . '.txt';
        $txtRelative   = 'excel_to_txt/' . $base . '-' . $suffix . '.txt';

        Storage::disk('public')->makeDirectory('excel_to_txt');
        Storage::disk('public')->put($txtRelative, $content);

        $this->downloadLink = Storage::url($txtRelative);
        $this->completed    = true;

        // Limpiar el Excel temporal
        Storage::disk('local')->delete($tmpPath);
    }

    /** Reinicia el componente para subir otro archivo */
    public function reset_form(): void
    {
        $this->reset(['excel', 'fileName', 'downloadLink', 'txtName', 'rowCount', 'completed']);
    }

    public function render()
    {
        return view('livewire.excel-to-txt-converter');
    }
}
