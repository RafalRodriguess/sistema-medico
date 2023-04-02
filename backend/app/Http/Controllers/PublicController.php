<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcessoExterno\AcessoExternoRequest;
use App\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PublicController extends Controller
{
    public function index()
    {
        return '';
    }

    public function acessoExterno(AcessoExternoRequest $request)
    {
        $dados = $request->validated();
        $instituicao = Instituicao::where('id', 7)->first();
        if(!empty($instituicao) && Hash::check($dados['codigo_acesso_terceiros'], $instituicao->codigo_acesso_terceiros)){
            return response()->json($dados);
        }

        return response()->json(false);
    }
}
