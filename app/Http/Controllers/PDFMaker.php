<?php

namespace App\Http\Controllers;
use App\Models\Receipt;
use App\Mail\ReceiptMail;
use App\Models\CompanyData;
use Barryvdh\DomPDF\Facade\Pdf;
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

        Mail::to($receipt->client->email)->send(new ReceiptMail($receipt, $pdf));

        return redirect()->route('receipt.index')
            ->with('success', 'Recibo enviado exitosamente a ' . $receipt->client->email . '.');
    }

    public function showPDF() {
        return view('dompdf.factura');
    }
}
