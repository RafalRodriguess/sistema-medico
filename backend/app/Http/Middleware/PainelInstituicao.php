<?php

namespace App\Http\Middleware;

use Closure;

class PainelInstituicao
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
        $usuario = $request->user('instituicao');

        if ($request->session()->has('instituicao')) {
            abort_unless($usuario->instituicao()->where('id', $request->session()->get('instituicao'))->exists(), 403);
            return $next($request);
        }

        
        $usuario->load('instituicao');

        if ($usuario->instituicao->count() === 1) {
            $request->session()->put('instituicao', $usuario->instituicao->first()->id);
            return $next($request);
        }

        return redirect()->route('instituicao.eu.instituicoes');
    }
}
