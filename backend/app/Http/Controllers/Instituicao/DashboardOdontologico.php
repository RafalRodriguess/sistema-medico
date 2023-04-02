<?php

namespace App\Http\Controllers\Instituicao;

use App\Convenio;
use App\GruposProcedimentos;
use App\Http\Controllers\Controller;
use App\OdontologicoItemPaciente;
use App\OdontologicoPaciente;
use App\Procedimento;
use Illuminate\Http\Request;
use App\Support\Outros;

class DashboardOdontologico extends Controller
{
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'dashboard_odontologico');
        return view('instituicao.dashboard_odontologico.index');
    }

    public function getQuantidade(Request $request)
    {
        $data = [
            date("Y-m-d 00:00:00", strtotime($request->input('start'))),
            date("Y-m-d 23:59:59", strtotime($request->input('end')))
        ];

        $orcamentos = OdontologicoPaciente::getOrcamentosDashboard($data)->get();
        
        $total_orcamentos = count($orcamentos);
        if($total_orcamentos == 0){
            $total_orcamentos = 1;
        }
        $total_criado = 0;
        $total_aprovado = 0;
        foreach ($orcamentos as $key => $value) {
            if ($value->status == 'criado') {
                $total = 0;
                foreach ($value->itens as $key => $item) {
                    if ($item->procedimento_instituicao_convenio_id){
                        $total += $item->procedimentos->valor;
                        $total += $item->desconto;
                    }
                }

                $total_criado += $total; 
            }else{
                if($value->status != 'reprovado'){
                    $total_aprovado += $value->valor_aprovado;
                }
            }
        }
        // dd($total_aprovado, $total_criado);
        $criados = OdontologicoPaciente::getQuantidades($data, 'criado')->first();
        $aprovados = OdontologicoPaciente::getQuantidades($data, 'aprovado')->first();
        $em_tratamentos = OdontologicoPaciente::getQuantidades($data, 'aprovado', 'em_tratamento')->first();
        $finalizados = OdontologicoPaciente::getQuantidades($data, 'aprovado', 'finalizados')->first();

        return view('instituicao.dashboard_odontologico.quantidades', compact('criados', 'aprovados', 'em_tratamentos', 'finalizados', 'total_criado', 'total_orcamentos', 'total_aprovado'));
    }

    public function getProcedimentos(Request $request)
    {
        $data = [
            date("Y-m-d 00:00:00", strtotime($request->input('start'))),
            date("Y-m-d 23:59:59", strtotime($request->input('end')))
        ];

        $dados = Procedimento::getProcedimentosDashboard($data)->get();
        $procedimentos = null;
        
        if(count($dados) > 0){
            foreach($dados as $k => $v){
                $procedimentos[$k]['cor'] = Outros::getbackground($k);
                $procedimentos[$k]['descricao'] = $v->descricao;
                $quantidade = 0;
                foreach ($v->procedimentoInstituicao[0]->conveniosProcedimentos as $key => $convenio) {
                    $quantidade += count($convenio->orcamentosItens);
                }
                $procedimentos[$k]['quantidade'] = $quantidade;
            }
        }
        
        return response()->json($procedimentos);
    }
    
    public function getConvenios(Request $request)
    {
        $data = [
            date("Y-m-d 00:00:00", strtotime($request->input('start'))),
            date("Y-m-d 23:59:59", strtotime($request->input('end')))
        ];

        $dados = Convenio::getConveniosDashboard($data)->get();
        $convenios = null;
        
        if(count($dados) > 0){
            foreach($dados as $k => $v){
                $convenios[$k]['cor'] = Outros::getbackground($k);
                $convenios[$k]['nome'] = $v->nome;
                $quantidade = 0;
                foreach ($v->conveniosProcedimentos as $key => $procedimentos) {
                    $quantidade += count($procedimentos->orcamentosItens);
                }
                $convenios[$k]['quantidade'] = $quantidade;
            }
        }
        
        return response()->json($convenios);
    }
    
    public function getGrupo(Request $request)
    {
        $data = [
            date("Y-m-d 00:00:00", strtotime($request->input('start'))),
            date("Y-m-d 23:59:59", strtotime($request->input('end')))
        ];

        $dados = GruposProcedimentos::getGrupoDashboard($data)->get();
        
        $grupos = null;
        
        if(count($dados) > 0){
            foreach($dados as $k => $v){
                $grupos[$k]['cor'] = Outros::getbackground($k);
                $grupos[$k]['nome'] = $v->nome;
                $quantidade = 0;
                foreach ($v->procedimentos_instituicoes as $key => $procedimentos) {
                    foreach ($procedimentos->conveniosProcedimentos as $key => $value) {
                        $quantidade += count($value->orcamentosItens);
                    }
                }
                $grupos[$k]['quantidade'] = $quantidade;
            }
        }
        
        return response()->json($grupos);
    }
    
    public function getProcedimentosRealizados(Request $request)
    {
        $data = [
            date("Y-m-d 00:00:00", strtotime($request->input('start'))),
            date("Y-m-d 23:59:59", strtotime($request->input('end')))
        ];

        $dados = Procedimento::getProcedimentosRealizadosDashboard($data)->get();
        $procedimentos_realizados = null;
        
        if(count($dados) > 0){
            $i = 0;
            foreach($dados as $k => $v){
                foreach ($v->procedimentoInstituicao[0]->conveniosProcedimentos as $c => $convenio) {
                    $quantidade = 0;
                    $i++;
                    if(count($convenio->orcamentosItens) > 0){
                        $procedimentos_realizados[$i]['convenio'] = $convenio->convenios->nome;
                        $procedimentos_realizados[$i]['descricao'] = $v->descricao;
                        $quantidade += count($convenio->orcamentosItens);
                        $procedimentos_realizados[$i]['quantidade'] = $quantidade;
                        $procedimentos_realizados[$i]['grupo'] = $v->procedimentoInstituicao[0]->grupoProcedimento->nome;
                    }
                }
            }
        }
        
        return view('instituicao.dashboard_odontologico.procedimentos_realizados', compact('procedimentos_realizados'));
    }
    
    public function getProcedimentosVendidos(Request $request)
    {
        $data = [
            date("Y-m-d 00:00:00", strtotime($request->input('start'))),
            date("Y-m-d 23:59:59", strtotime($request->input('end')))
        ];

        $dados = Procedimento::getProcedimentosVendidosDashboard($data)->get();
        $procedimentos_vendidos = null;
        
        if(count($dados) > 0){
            $i = 0;
            foreach($dados as $k => $v){
                foreach ($v->procedimentoInstituicao[0]->conveniosProcedimentos as $c => $convenio) {
                    $quantidade = 0;
                    $i++;
                    if(count($convenio->orcamentosItens) > 0){
                        $procedimentos_vendidos[$i]['convenio'] = $convenio->convenios->nome;
                        $procedimentos_vendidos[$i]['descricao'] = $v->descricao;
                        $quantidade += count($convenio->orcamentosItens);
                        $procedimentos_vendidos[$i]['quantidade'] = $quantidade;
                        $procedimentos_vendidos[$i]['grupo'] = $v->procedimentoInstituicao[0]->grupoProcedimento->nome;
                    }
                }
            }
        }
        
        return view('instituicao.dashboard_odontologico.procedimentos_vendidos', compact('procedimentos_vendidos'));
    }
}
