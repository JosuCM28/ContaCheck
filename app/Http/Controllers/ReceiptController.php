<?php

namespace App\Http\Controllers;
use App\Models\Client;
use App\Models\Counter;
use App\Models\Receipt;
use App\Models\Category;
use App\Mail\ReceiptMail;
use App\Models\CompanyData;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\TimbradoService;
use Illuminate\Support\Facades\Mail;

class ReceiptController extends Controller
{
    public function index()
    {

        return view('receipts.index');

    }

    public function create()
    {
        $counters = Counter::all();
        $categories = Category::all();
        $clients = Client::with('counter')->get();
        $identificator = Str::uuid();



        return view('receipts.create', compact('counters', 'categories', 'clients', 'identificator'));
    }

    public function show($identificator)
    {
        $receipt = Receipt::findOrFail($identificator);
        return view('receipts.show', compact('receipt'));
    }

    public function edit($receipt){

        $receipt = Receipt::findOrFail($receipt);
        $receipt->payment_date = Carbon::parse($receipt->payment_date)->format('Y-m-d');
        $categories = Category::all();
        $counters = Counter::all();
        $clients = Client::all();
        return view ('receipts.edit', compact('receipt', 'categories','counters','clients'));

    }

    public function store(Request $request)
    {
        $response = [];
        $rfcReceptor = "";

        $request->validate([
            'counter_id' => 'required|exists:counters,id',
            'client_id' => 'required|exists:clients,id',
            'category_id' => 'required|exists:categories,id',
            'identificator' => 'required|string|max:255|unique:receipts',
            'payment_date' => 'required|date',
            'pay_method' => 'required|string',
            'mount' => 'required',
            'concept' => 'required|string',
            'status' => 'required|string',
        ]);

        $receipt = Receipt::create([
            'counter_id' => $request->input('counter_id'),
            'client_id' => $request->input('client_id'),
            'category_id' => $request->input('category_id'),
            'identificator' => $request->input('identificator'),
            'payment_date' => $request->input('payment_date'),
            'pay_method' => $request->input('pay_method'),
            'mount' => $request->input('mount'),
            'concept' => $request->input('concept'),
            'status' => $request->input('status'),
        ]);

        if ($request->input('timbrarInput') == 'true') {
            $total = $request->input('mount');
            $subtotal = round($total / 1.16, 2);
            $iva = round($total - $subtotal, 2);

            $client = Client::find($request->input('client_id'));
            $company = CompanyData::first();

            $rfcReceptor = $client->rfc;
            
            $data = [
                'regimenEmisor' => $company->regime->code,
                'codigoPostalCompany' => $company->cp,
                'forma_pago' => $request->input('pay_method') === 'EFECTIVO' ? '01' : '03',
                'subtotal' => (string) $subtotal,
                'iva' => (string) $iva,
                'total' => (string) $total,
                'rfcReceptor' => $rfcReceptor,
                'nombreReceptor' => $client->full_name,
                'regimenFiscalReceptor' => $client->regime->code,
                'domicilioFiscalReceptor' => $client->cp,
                'estado' => $client->state,
                'localidad' => $client->localities,
                'municipio' => $client->city,
                'calle' => $client->street,
                'colonia' => $client->col,
                'noExterior' => $client->num_ext,
                'codigoPostal' => $client->cp,
                'concepto_descripcion' => $request->input('concept'),
            ];

            $service = new TimbradoService($data);
            $response = $service->timbrar();

            $receipt->is_timbred = true;
            $receipt->uuid = $response['uuid'];
            $receipt->sello = substr($response['sello'], -8);
            $receipt->save();
        }

        if ($request->input('action') == 'send') {
            $company = CompanyData::first();

            if (!empty($response)) {
                $rfcEmisor = config('services.facturafiel.rfc');
                $url = "https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?id={$response['uuid']}&re={$rfcEmisor}&rr={$rfcReceptor}&tt={$response['total']}&fe={$response['sello']}";
                
            } else {
                $url = route('receipt.verify', $receipt->identificator);
            }
            
            $pdf = Pdf::loadView('dompdf.factura', compact('receipt', 'url', 'company'))->setPaper('a4', 'portrait')->output();

            Mail::to($receipt->client->email)->send(new ReceiptMail($receipt, $pdf));

            return redirect()->route('receipt.create')->with('toast', [
                'title' => 'Recibo creado',
                'message' => 'Recibo creado y enviado correctamente.',
                'type' => 'success',
            ]);
        }
        
        return redirect()->route('receipt.create')->with('toast', [
            'title' => 'Recibo creado',
            'message' => 'Recibo creado y almacenado exitosamente.',
            'type' => 'success',
        ]);
    }

    public function update(Request $request, Receipt $receipt){
        
        $request->validate([
            'counter_id' => 'required|exists:counters,id',
            'client_id' => 'required|exists:clients,id',
            'category_id' => 'required|exists:categories,id',
            'payment_date' => 'required|string',
            'pay_method' => 'required|string',
            'mount' => 'required',
            'description' => 'required|string',
            'status' => 'required|string',
            
        ]);
        $receipt->update($request->all());
        return redirect()->route('receipt.show', ['identificator' => $receipt->id])->with('toast', [
            'title' => 'Recibo actualizado',
            'message' => 'Recibo actualizado correctamente.',
            'type' => 'success',
        ]);

    }

    public function destroy(Receipt $receipt){

        $receipt = Receipt::findOrFail($receipt->id);
        $receipt->delete();
        return redirect()->route('receipt.index')->with('toast', [
            'title' => 'Recibo borrado',
            'message' => 'Recibo borrado correctamente.',
            'type' => 'success',
        ]);


    }

    public function timbrarRecibo($id) {
        $receipt = Receipt::findOrFail($id);
        $company = CompanyData::first();

        $total = $receipt->mount;
        $subtotal = round($total / 1.16, 2);
        $iva = round($total - $subtotal, 2);

        $data = [
            'regimenEmisor' => $company->regime->code,
            'forma_pago' => $receipt->pay_method === 'EFECTIVO' ? '01' : '03',
            'codigoPostalCompany' => $company->cp,
            'subtotal' => (string) $subtotal,
            'iva' => (string) $iva,
            'total' => (string) $total,
            'rfcReceptor' => $receipt->client->rfc,
            'nombreReceptor' => $receipt->client->full_name,
            'regimenFiscalReceptor' => $receipt->client->regime->code,
            'domicilioFiscalReceptor' => $receipt->client->cp,
            'estado' => $receipt->client->state,
            'localidad' => $receipt->client->localities,
            'municipio' => $receipt->client->city,
            'calle' => $receipt->client->street,
            'colonia' => $receipt->client->col,
            'noExterior' => $receipt->client->num_ext,
            'codigoPostal' => $receipt->client->cp,
            'concepto_descripcion' => $receipt->concept,
        ];

        $service = new TimbradoService($data);
        $response = $service->timbrar();

        if ($response['uuid']) {
            $receipt->is_timbred = true;
            $receipt->uuid = $response['uuid'];
            $receipt->sello = substr($response['sello'], -8);
            $receipt->save();

            return redirect()->route('receipt.show', ['identificator' => $receipt->id])->with('toast', [
                'title' => 'Recibo timbrado',
                'message' => 'Recibo timbrado correctamente.',
                'type' => 'success',
            ]);
        }

        return redirect()->route('receipt.show', ['identificator' => $receipt->id])->with('toast', [
            'title' => 'Recibo timbrado',
            'message' => 'Recibo timbrado correctamente.',
            'type' => 'success',
        ]);
    }
    
    // public function destroydos(Receipt $receipt2){

    //     $receipt = Receipt::findOrFail($receipt2->id);
    //     $receipt->delete();
    //     return redirect()->refresh()->with('toast', []);

    // }
}