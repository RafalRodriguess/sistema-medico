<?php

namespace App\Http\Controllers\Comercial;

use App\HorarioFuncionamentoComercial;
use App\Http\Controllers\Controller;
use App\Http\Requests\ComercialLoja\HorariosFuncionamentoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HorariosFuncionamento extends Controller
{
    public function index(Request $request){
        $this->authorize('habilidade_comercial_sessao', 'editar_horarios_funcionamento');

        $comercial_id = $request->session()->get('comercial');
        $horarios = HorarioFuncionamentoComercial::where('comercial_id', $comercial_id)->get();
        
        return view('comercial.horarios_funcionamento/editar', \compact('horarios'));
    }

    public function update(HorariosFuncionamentoRequest $request)
    {
        $this->authorize('habilidade_comercial_sessao', 'editar_horarios_funcionamento');
        $comercial_id = $request->session()->get('comercial');
        
        DB::transaction(function() use($request, $comercial_id){
            for ($i=0; $i < 7; $i++) { 
                $dados = [
                    'horario_inicio' => $this->RetornaHorarioIncio($i, $request),
                    'horario_fim' => $this->RetornaHorarioFim($i, $request),
                    'full_time' => $this->RetornaFullTime($i, $request),
                    'fechado' => $this->RetornaFechado($i, $request),
                ];

                $horario = HorarioFuncionamentoComercial::where('id', $request->validated()['horario']['id'][$i])->first();

                $horario->update($dados);

                $usuario_logado = $request->user('comercial');
                $horario->criarLogEdicao($usuario_logado, $comercial_id);
            }
        });

        $horarios = HorarioFuncionamentoComercial::where('comercial_id', $comercial_id)->get();

        return redirect()->route('comercial.horarios_funcionamento.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Horarios atualizados com sucesso!'
        ]);
    }

    private function RetornaHorarioIncio($posicao, $request)
    {
        if(array_key_exists('horario_inicio', $request->validated()['horario'])){
            if(array_key_exists($posicao, $request->validated()['horario']['horario_inicio'])){
                return $request->validated()['horario']['horario_inicio'][$posicao];
            }else{
                return null;
            }
        }
    }

    private function RetornaHorarioFim($posicao, $request)
    {
        if(array_key_exists('horario_fim', $request->validated()['horario'])){
            if(array_key_exists($posicao, $request->validated()['horario']['horario_fim'])){
                return $request->validated()['horario']['horario_fim'][$posicao];
            }else{
                return null;
            }
        }
    }

    private function RetornaFullTime($posicao, $request)
    {
        if(array_key_exists('full_time', $request->validated()['horario'])){
            if(array_key_exists($posicao, $request->validated()['horario']['full_time'])){
                return true;
            }else{
                return false;
            }
        }
    }
    
    private function RetornaFechado($posicao, $request)
    {
        if(array_key_exists('fechado', $request->validated()['horario'])){
            if($request->validated()['horario']['fechado']){
                if(array_key_exists($posicao, $request->validated()['horario']['fechado'])){
                    return true;
                }else{
                    return false;
                }
            }
        }
    }
}
