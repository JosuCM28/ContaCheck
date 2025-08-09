<?php

namespace App\Http\Controllers;

use App;
use App\Models\Counter;
use App\Models\User;
use App\Models\Regime;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class CounterController extends Controller
{
    public function index()
    {
        $counters = Counter::all();
        return view('counters.index', compact('counters'));
    }

    public function create()
    {
        $password = Str::random(10);
        $regimes = Regime::all();
        return view('counters.create', compact('password', 'regimes'));

    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|string|unique:counters|max:15', // Teléfono único, puede estar vacío, máximo 15 caracteres
            'name' => 'required|string|max:255', // Nombre requerido y en formato de texto
            'last_name' => 'required|string|max:255', // Apellido requerido y en formato de texto
            'address' => 'required|string|max:255', // Dirección puede estar vacía, máximo 255 caracteres
            'rfc' => 'required|string|unique:counters|max:13', // RFC único con formato
            'curp' => 'nullable|string|unique:counters|max:18', // CURP único con formato
            'city' => 'nullable|string|max:100', // Ciudad puede estar vacía, máximo 100 caracteres
            'state' => 'nullable|string|max:100', // Estado puede estar vacío, máximo 100 caracteres
            'cp' => 'required|string|max:5|regex:/^\d{5}$/', // Código Postal, máximo 5 dígitos
            'regime_id' => 'required|string|max:50', // Régimen puede estar vacío, máximo 50 caracteres
            'birthdate' => 'nullable|date|before:today', // Fecha de nacimiento válida y antes de hoy
            'email' => 'required|email|unique:users|max:255', // Email requerido, válido, único y máximo 255 caracteres
            'password' => 'required|string|min:8', // Contraseña requerida, mínimo 8 caracteres, confirmada
        ]);
        $user = User::create([
            'password' => $request->password,
            'email' => $request->email,
            'name' => $request->name,
            'rol' => 'contador',
        ]);

        $fullname = $request->name . ' ' . $request->last_name;
        Counter::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'name' => $request->name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'rfc' => $request->rfc,
            'curp' => $request->curp,
            'city' => $request->city,
            'state' => $request->state,
            'cp' => $request->cp,
            'regime_id' => $request->regime_id,
            'birthdate' => $request->birthdate,
            'full_name' => $fullname,
        ]);

        return redirect()->route('counter.index')->with('success', 'Contador creado exitosamente.');
    }

    public function show(Counter $counter, Regime $regime, Document $document)
    {
        return view('counters.show', [
            'counter' => $counter,
            'user' => $counter->user,
            'regime' => $regime,
            'document' => $document,
        ]);
    }

    public function edit(Counter $counter)
    {


       return view('counters.edit', [
            'counter' => $counter->load('regime', 'user'),
            'regimes' => Regime::all(),
        ]);
    }

    public function update(Request $request, Counter $counter, User $user)
    {
        
        $fullname = $request->name . ' ' . $request->last_name;
        
        $request->validate([
            'name' => 'required|string|max:255,', 
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255', 
            'rfc' => 'required|string|size:13', 
            'curp' => 'nullable|string|size:18', 
            'phone' => 'nullable|string|size:10',
            'birthdate' => 'nullable|date',
            'city' => 'nullable|string|max:255',
            'cp' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'regime_id' => 'required',
        ]);
        
        $request->merge(['full_name' => $fullname]);
        
        $counter->update($request->all());
        $user = User::findOrFail($counter->user_id);
        $user->update($request->only(['name', 'email']));

        return redirect()->route('counter.index')->with('success', 'Contador actualizado exitosamente.');
        
    }

    public function destroy(Counter $counter)
    {
        $counter = Counter::findOrFail($counter->id);
        $counter->delete();
        return redirect()->route('counter.index')->with('success', 'Contador Borrado Exitosamente');
    }

}
