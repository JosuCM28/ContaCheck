<?php

namespace App\Http\Controllers;
use App\Models\CompanyData;
use App\Models\Regime;
use Illuminate\Http\Request;

class DataEmisorController extends Controller
{
    public function index()
    {
        $company = CompanyData::first() ?? new CompanyData;
        return view('emisor.dataemisor', compact('company'));
    }
    public function edit()
    {
        $regimes = Regime::all();
        $company = CompanyData::first() ?? new CompanyData;

        return view('emisor.edit', [
            'regimes' => $regimes,
            'company' => $company,
        ]);
    }

    public function update(Request $request)
    {
        // Validar los campos del formulario
        $request->validate([
            'regime_id' => 'required|exists:regimes,id',
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'curp' => 'nullable|string|max:18',
            'cp' => 'required|string|max:5',
            'rfc' => 'required|string|max:13',
            'phone' => 'nullable|string|max:10',
            'phone2' => 'nullable|string|max:10',
            'email' => 'required|email|max:255',
            'street' => 'required|string|max:255',
            'num_ext' => 'nullable|string|max:10',
            'col' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'localities' => 'nullable|string|max:255',
            'referer' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'full_name' => 'nullable|string|max:255',
        ]);

        // Actualizar nombre completo
        $request->merge([
            'full_name' => $request->name . ' ' . $request->last_name
        ]);

        // Actualizar o crear los datos
        CompanyData::updateOrCreate([], $request->all());

        return redirect()->route('emisor.index')->with('success', 'Datos del emisor actualizados exitosamente.');
    }

}
