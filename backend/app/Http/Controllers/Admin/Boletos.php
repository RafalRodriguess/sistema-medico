<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Boletos\PesquisaBoletosRequest;
use App\Instituicao;
use Illuminate\Http\Request;

class Boletos extends Controller
{
    public function index()
    {
        $instituicoes = Instituicao::get();

        return view('admin.relatorio_boletos.index', \compact('instituicoes'));
    }

    public function tabela(PesquisaBoletosRequest $request)
    {
        $data = $request->validated();

        $dados = Instituicao::getTotalBoletosInstituicoes($data)->get();

        return view('admin.relatorio_boletos.tabela', \compact('dados'));
    }
}
