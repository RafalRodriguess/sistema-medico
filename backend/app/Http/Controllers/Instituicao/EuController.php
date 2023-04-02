<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Instituicao;
use Illuminate\Http\Request;

class EuController extends Controller
{
    public function instituicoes(Request $request)
    {
        return view('instituicao.instituicao_loja.lista_escolher');
    }

    public function accessarInstituicoes(Request $request, Instituicao $instituicao)
    {
        $usuario = $request->user('instituicao');
        abort_unless($instituicao->instituicaoUsuarios()->where('id', $usuario->id)->exists(), 403);

        $request->session()->put('instituicao', $instituicao->id);

        return redirect('/instituicao');
    }
}
