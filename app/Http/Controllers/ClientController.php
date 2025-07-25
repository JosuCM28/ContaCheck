<?php

namespace App\Http\Controllers;
use App\Models\Counter;
use App\Models\Regime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Credential;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::all();
        $counters = Counter::all();
        return view('clients.index', compact('clients', 'counters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $counters = Counter::all();
        $regimes = Regime::all();
        $credentials = Credential::all();
        $token = Str::random(8);

        return view('clients.create', compact('counters', 'token', 'regimes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable',
            'counter_id' => 'required',
            'regime_id' => 'required',
            'credential_id' => 'nullable',
            'status' => 'required',
            'phone' => 'nullable|string|unique:clients|max:10',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'rfc' => 'required|string|unique:clients|max:13',
            'curp' => 'nullable|string|unique:clients|max:18',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'cp' => 'required|string|max:5',
            'nss' => 'nullable|string|max:11',
            'note' => 'nullable|string|max:500',
            'token' => 'required|string|size:8|unique:clients',
            'birthdate' => 'nullable|date|before:today',

            // Validación de credenciales
            'client_id' => 'nullable',
            'idse' => 'nullable|string|max:255',
            'sipare' => 'nullable|string|max:255',
            'siec' => 'nullable|string|max:255',
            'useridse' => 'nullable|string|max:255',
            'usersipare' => 'nullable|string|max:255',
            'auxone' => 'nullable|string|max:255',
            'auxtwo' => 'nullable|string|max:255',
            'auxthree' => 'nullable|string|max:255',
            'iniciofiel' => 'nullable',
            'finfiel' => 'nullable',
            'iniciosello' => 'nullable',
            'finsello' => 'nullable',
        ]);

        $fullname = $request->name . ' ' . $request->last_name;
        $client = Client::create([
            'user_id' => $request->user_id,
            'counter_id' => $request->counter_id,
            'status' => $request->status,
            'phone' => $request->phone,
            'name' => $request->name,
            'email' => $request->email,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'rfc' => $request->rfc,
            'curp' => $request->curp,
            'city' => $request->city,
            'state' => $request->state,
            'cp' => $request->cp,
            'nss' => $request->nss,
            'regime_id' => $request->regime_id,
            'note' => $request->note,
            'token' => $request->token,
            'birthdate' => $request->birthdate,
            'full_name' => $fullname,
        ]);
        Credential::create([
            'sipare' => $request->sipare,
            'idse' => $request->idse,
            'siec' => $request->siec,
            'useridse' => $request->useridse,
            'usersipare' => $request->usersipare,
            'auxone' => $request->auxone,
            'auxtwo' => $request->auxtwo,
            'auxthree' => $request->auxthree,
            'client_id' => $client->id,
            'iniciofiel' => $request->iniciofiel,
            'finfiel' => $request->finfiel,
            'iniciosello' => $request->iniciosello,
            'finsello' => $request->finsello,

        ]);

        return redirect()->route('client.index')->with('success', 'Cliente creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return view('clients.show', [
            'client' => $client,
            'credential' => Credential::where('client_id', $client->id)->first(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        $regimes = Regime::all();
        $counters = Counter::all();
        return view('clients.edit', [
            'client' => $client,
            'regimes' => $regimes,
            'counters' => $counters
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        // Validar y actualizar el post
        $request->validate([
            'counter_id' => 'required',

            'phone' => 'nullable|string|max:10',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'rfc' => 'required|string|max:13',
            'curp' => 'nullable|string|max:18',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'cp' => 'required|string|max:5',
            'nss' => 'nullable|string|max:11',
            'regime_id' => 'required|string|max:255',
            'note' => 'nullable|string|max:500',
            'status' => 'nullable',
            'password' => 'nullable',
            'birthdate' => 'nullable|date|before:today',

            # Validación de credenciales
            'auxone' => 'nullable|string|max:255',
            'auxtwo' => 'nullable|string|max:255',
            'auxthree' => 'nullable|string|max:255',
            'idse' => 'nullable|string|max:255',
            'sipare' => 'nullable|string|max:255',
            'siec' => 'nullable|string|max:255',
            'useridse' => 'nullable|string|max:255',
            'usersipare' => 'nullable|string|max:255',
            'iniciofiel' => 'nullable',
            'iniciosello' => 'nullable',
            'finsello' => 'nullable',
            'finfiel' => 'nullable',
        ]);
        $client = Client::findOrFail($client->id);
        $client->full_name = $request->name . ' ' . $request->last_name;
        $client->update($request->all());
        $client->credentials->update($request->all());
        return redirect()->route('client.index')->with('success', 'Cliente actualizado exitosamente.');
    }


    public function destroy(Client $client)
    {
        $client = Client::findOrFail($client->id);
        $client->delete();
        return redirect()->route('client.index')->with('success', 'Contador Borrado Exitosamente');
    }

    public function final(Client $client)
    {

        $client = Auth::user()->client;

        return view('userclient.index', [
            'client' => $client,
        ]);
    }
}