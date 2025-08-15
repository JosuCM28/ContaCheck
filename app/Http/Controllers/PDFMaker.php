<?php

namespace App\Http\Controllers;
use App\Models\Receipt;
use App\Mail\ReceiptMail;
use App\Models\CompanyData;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\EvolutionService;
use Illuminate\Support\Facades\Mail;

class PDFMaker extends Controller
{
    public function downloadPDF($id)
    {
        $receipt = Receipt::findOrFail($id);
        $company = CompanyData::first();

        if ($receipt->is_timbred) {
            $rfcEmisor = config('services.facturafiel.rfc');
            $url = "https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?" .
                "id={$receipt->uuid}&" .
                "re={$rfcEmisor}&" .
                "rr={$receipt->client->rfc}&" .
                "tt=" . sprintf('%017.6f', (float) $receipt->mount) . "&" .
                "fe={$receipt->sello}";
        } else {
            $url = route('receipt.verify', $receipt->identificator);
        }

        $pdf = Pdf::loadView('dompdf.factura', compact('receipt', 'url', 'company'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('recibo.pdf');
    }

    public function sendPDF($id)
    {
        $receipt = Receipt::findOrFail($id);
        $company = CompanyData::first();

        if ($receipt->is_timbred) {
            $rfcEmisor = config('services.facturafiel.rfc');
            $url = "https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?" .
                "id={$receipt->uuid}&" .
                "re={$rfcEmisor}&" .
                "rr={$receipt->client->rfc}&" .
                "tt=" . sprintf('%017.6f', (float) $receipt->mount) . "&" .
                "fe={$receipt->sello}";
        } else {
            $url = route('receipt.verify', $receipt->identificator);
        }

        $pdf = Pdf::loadView('dompdf.factura', compact('receipt', 'url', 'company'))
            ->setPaper('a4', 'portrait')
            ->output();

        if (!empty($receipt->client->email)) {
            Mail::to($receipt->client->email)->send(new ReceiptMail($receipt, $pdf));
        }

        if (!empty($receipt->client->phone)) {
            $dataEvolution = [
                'number' => $receipt->client->phone,
                'pdf_data' => base64_encode($pdf),
                'concept' => $receipt->concept,
                'payment_date' => $receipt->payment_date,
            ];

            $serviceEvolution = new EvolutionService();
            $res = $serviceEvolution->sendMessage($dataEvolution);

            if (!$res) {
                return redirect()->route('receipt.create')->with('toast', [
                    'title' => 'No se pudo enviar el recibo',
                    'message' => 'Hubo un problema al enviar el recibo por WhatsApp.',
                    'type' => 'error',
                ]);
            }
        } else {
            return redirect()->route('receipt.create')->with('toast', [
                'title' => 'No se pudo enviar el recibo',
                'message' => 'Hubo un problema al enviar el recibo por WhatsApp.',
                'type' => 'success',
            ]);
        }

        return redirect()->route('receipt.index')
            ->with('toast', [
                'title' => 'Recibo enviado correctamente',
                'message' => 'Recibo enviado por correo y WhatsApp exitosamente.',
                'type' => 'success',
            ]);
    }

    public function showPDF() {
        return view('dompdf.factura');
    }
}
