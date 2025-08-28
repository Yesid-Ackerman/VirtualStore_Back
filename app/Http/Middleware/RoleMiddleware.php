<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
     public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        // nombre del rol del usuario (normalizado)
        $userRole = strtolower(optional($user->role)->name ?? '');

        // normaliza roles permitidos
        $allowed = array_map('strtolower', $roles ?: []);

        if (! in_array($userRole, $allowed, true)) {
            return response()->json(['message' => 'No tienes permisos para acceder a esta ruta'], 403);
        }

        return $next($request);
    }
}
