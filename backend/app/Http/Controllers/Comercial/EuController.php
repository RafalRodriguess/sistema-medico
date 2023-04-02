<?php

namespace App\Http\Controllers\Comercial;

use App\Comercial;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EuController extends Controller
{
    public function comerciais(Request $request)
    {
        $usuario = $request->user('comercial');

        $usuario->comercial->map(function (Comercial $comercial) {
            $comercial = $comercial;
            // dump(route('comercial.eu.escolher_comercial', [$comercial]));
        });

        return view('comercial.comercial_loja.lista_escolher', \compact('usuario'));
    }

    public function accessarComerciais(Request $request, Comercial $comercial)
    {
        $usuario = $request->user('comercial');
        abort_unless($comercial->comercialUsuarios()->where('id', $usuario->id)->exists(), 403);

        $request->session()->put('comercial', $comercial->id);

        return redirect('/comercial');
    }
}