<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomRegisterController extends Controller
{

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required|string|exists:clients,token',
        ]);
        
        $client = Client::where('token', $request->token)->first();
        if (!$client || $client->user_id) {
            return redirect()->back()->withErrors(['token' => 'Token inválido o ya utilizado']);
        }

        $user = User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password' => Hash::make($request->password),
        ]);
        $client->user_id = $user->id;
        $client->save();
        Auth::login($user);
        return redirect()->route('dashboard');

    }

}
