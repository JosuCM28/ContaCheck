<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receipt;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\Credential;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $tasks = Task::where('status', 'todo')->get();

        $kpiRecibosMes = Receipt::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $kpiMontoTotalMes = Receipt::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('mount');

        $kpiClientesNuevos = Client::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Lista de últimos 5 clientes nuevos del mes
        $clientesNuevosMes = Client::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $ultimosRecibos = Receipt::with('client', 'category')
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard', compact(
            'kpiRecibosMes',
            'kpiMontoTotalMes',
            'kpiClientesNuevos',
            'clientesNuevosMes',
            'ultimosRecibos',
            'tasks',
        ));
    }
}
