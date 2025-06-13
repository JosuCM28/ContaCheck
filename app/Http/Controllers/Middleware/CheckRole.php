<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next)
    {
        // Verifica si el usuario está autenticado
        if (!Auth::check()) {
            abort(403, 'Acceso denegado.');
        }

        $user = Auth::user();

        // Si el rol es contador, puede acceder a todas las rutas
        if ($user->rol === 'contador') {
            return $next($request);
        }

        // Si el rol es cliente, solo permite acceso a client.final
        if ($user->rol === 'cliente') {
            if ($request->route()->getName() === 'client.final') {
                return $next($request);
            }
            return redirect()->route('client.final');
        }

        // Si el rol no es válido, denegar acceso
        abort(403, 'Acceso denegado.');
    }
}
