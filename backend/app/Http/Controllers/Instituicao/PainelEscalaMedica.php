<?php

namespace App\Http\Controllers\Instituicao;

use App\EscalasPrestadores;
use App\Especialidade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Instituicao;


class PainelEscalaMedica extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $this->authorize('habilidade_instituicao_sessao', 'visualizar_estoque_entrada');
       $instituicao = Instituicao::find(session()->get('instituicao'));

       $escalasMedicas = $instituicao->escalasMedicas()
                                        ->with('especialidade')->with('escalaPrestadores')->with('origem')->with('instituicao')
                                        ->join('escalas_prestadores','escala_medica_id','=','escalas_medicas.id')
                                        ->join('especialidades','especialidades.id','=','escalas_medicas.especialidade_id')
                                        ->join('prestadores','prestadores.id','=','escalas_prestadores.prestador_id')
                                        ->where('data',date('Y-m-d'))
                                        ->where('entrada','<',date('H:i:s'))
                                        ->where('saida','>',date('H:i:s'))->get();

        return view('instituicao.painel_escala_medica/lista',compact('escalasMedicas'));
    }

}
