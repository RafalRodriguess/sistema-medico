<?php

namespace App\Http\Middleware;

use Closure;

class PainelComercial
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $usuario = $request->user('comercial');

        if ($request->session()->has('comercial')) {
            abort_unless($usuario->comercial()->where('id', $request->session()->get('comercial'))->exists(), 403);
            return $next($request);
        }

        
        $usuario->load('comercial');

        if ($usuario->comercial->count() === 1) {
            $request->session()->put('comercial', $usuario->comercial->first()->id);
            return $next($request);
        }

        return redirect()->route('comercial.eu.comerciais');
    }
}
