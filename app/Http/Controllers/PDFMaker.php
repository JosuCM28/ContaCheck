<?php

namespace App\Http\Controllers;
use App\Models\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\ReceiptMail;
use Illuminate\Support\Facades\Mail;
class PDFMaker extends Controller
{
    public function downloadPDF($id)
    {
        $receipt = Receipt::findOrFail($id);

        if ($receipt->is_timbred) {
            $rfcEmisor = env('FACTURAFIEL_RFC');
            $url = "https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?" .
                "id={$receipt->uuid}&" .
                "re={$rfcEmisor}&" .
                "rr={$receipt->client->rfc}&" .
                "tt=" . sprintf('%017.6f', (float) $receipt->mount) . "&" .
                "fe={$receipt->sello}";
        } else {
            $url = route('receipt.verify', $receipt->identificator);
        }

        $pdf = Pdf::loadView('dompdf.factura', compact('receipt', 'url'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('recibo.pdf');
    }

    public function sendPDF($id)
    {
        $receipt = Receipt::findOrFail($id);

        if ($receipt->is_timbred) {
            $rfcEmisor = env('FACTURAFIEL_RFC');
            $url = "https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?" .
                "id={$receipt->uuid}&" .
                "re={$rfcEmisor}&" .
                "rr={$receipt->client->rfc}&" .
                "tt=" . sprintf('%017.6f', (float) $receipt->mount) . "&" .
                "fe={$receipt->sello}";
        } else {
            $url = route('receipt.verify', $receipt->identificator);
        }

        $pdf = Pdf::loadView('dompdf.factura', compact('receipt', 'url'))
            ->setPaper('a4', 'landscape')
            ->output();

        Mail::to($receipt->client->email)->send(new ReceiptMail($receipt, $pdf));

        return redirect()->route('receipt.index')
            ->with('success', 'Recibo enviado exitosamente a ' . $receipt->client->email . '.');
    }

}
