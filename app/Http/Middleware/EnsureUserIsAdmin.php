<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware que verifica que el usuario autenticado sea administrador.
 * Si no es admin, redirige o devuelve 403.
 */
class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No autenticado'], 401);
            }
            return redirect()->route('home');
        }

        $user = Auth::user();

        if (!$user->can('admin')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No autorizado'], 403);
            }
            
            return redirect()->route('home')
                ->with('error', 'No tienes permisos para acceder a esta sección');
        }

        return $next($request);
    }
}
