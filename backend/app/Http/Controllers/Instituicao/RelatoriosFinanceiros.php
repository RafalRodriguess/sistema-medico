<?php

namespace App\Http\Controllers\Instituicao;

use App\ContaPagar;
use App\Exports\RelatoriosFinanceirosExport;
use App\Http\Controllers\Controller;
use App\Instituicao;
use App\PlanoConta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;
use PhpParser\ErrorHandler\Collecting;

class RelatoriosFinanceiros extends Controller
{
    public function getExcel($export, $titulo, $excel, $tipo)
    {   
        return $excel->download($export, "{$titulo} ".date('YmdHis').".{$tipo}");
    }
    
    public function aPagar(Request $request){
        
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_contas_a_pagar');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $contas = $instituicao->contas()->get();
        $formaPagamentos = ContaPagar::formas_pagamento();
        $planosConta = $instituicao->planosContas()->getDespesas()->get();

        return view('instituicao.relatorios.financeiro.lista_a_pagar', \compact('contas', 'formaPagamentos', 'planosConta', 'instituicao'));
        
    }

    public function aPagarTabela(Request $request, Excel $excel){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $dados = $request->all();

        $dados = $instituicao->contasPagar()->relatorioContasPagar($dados)->where('status', 0)->get();

        return view('instituicao.relatorios.financeiro.tabela_a_pagar', \compact('dados'));
    }

    public function aReceber(Request $request){
        
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_contas_a_receber');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $contas = $instituicao->contas()->get();
        $formaPagamentos = ContaPagar::formas_pagamento();
        $planosConta = $instituicao->planosContas()->getReceitas()->get();

        return view('instituicao.relatorios.financeiro.lista_a_receber', \compact('contas', 'formaPagamentos', 'planosConta', 'instituicao'));
        
    }

    public function aReceberTabela(Request $request){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $dados = $request->all();

        // dd($dados);

        $dados = $instituicao->contasReceber()->relatorioContasReceber($dados)->where('status', 0)->where('cancelar_parcela', 0)->get();

        return view('instituicao.relatorios.financeiro.tabela_a_receber', \compact('dados'));
    }

    public function pagas(Request $request){
        
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_contas_pagas');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $contas = $instituicao->contas()->get();
        $formaPagamentos = ContaPagar::formas_pagamento();
        $planosConta = $instituicao->planosContas()->getDespesas()->get();

        return view('instituicao.relatorios.financeiro.lista_pagas', \compact('contas', 'formaPagamentos', 'planosConta', 'instituicao'));
    }

    public function pagasTabela(Request $request, Excel $excel){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $dados = $request->all();

        $dados = $instituicao->contasPagar()->relatorioContasPagar($dados)->where('status', 1)->get();

        return view('instituicao.relatorios.financeiro.tabela_pagas', \compact('dados'));
        
    }

    public function recebidas(Request $request){
        
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_contas_recebidas');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $contas = $instituicao->contas()->get();
        $formaPagamentos = ContaPagar::formas_pagamento();
        $planosConta = $instituicao->planosContas()->getReceitas()->get();

        return view('instituicao.relatorios.financeiro.lista_recebidas', \compact('contas', 'formaPagamentos', 'planosConta', 'instituicao'));
        
    }

    public function recebidasTabela(Request $request, Excel $excel){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $dados = $request->all();

        $dados = $instituicao->contasReceber()->relatorioContasReceber($dados)->where('status', 1)->where('cancelar_parcela', 0)->get();

        return view('instituicao.relatorios.financeiro.tabela_recebidas', \compact('dados'));
    }

    public function fluxoCaixa(Request $request){
        
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_fluxo_de_caixa');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $contas = $instituicao->contas()->get();
        $formaPagamentos = ContaPagar::formas_pagamento();
        $planosConta = $instituicao->planosContas()->get();

        return view('instituicao.relatorios.financeiro.fluxo_caixa', \compact('contas'));
        
    }

    public function fluxoCaixatabela(Request $request){
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorio_fluxo_de_caixa');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $dados = $request->all();


        $data = [$dados['data_inicio'], $dados['data_fim']];

        if($dados["tipo"] == "diario"){
            $date = "%d/%m";
        }else if($dados["tipo"] == "mensal"){
            $date = "%m/%Y";
        }

        $total = [];

        $contasRecebidas = $instituicao->contasReceber()
            ->whereBetween('data_pago', $data)
            ->when($dados['contas'], function($q) use($dados){
                $q->whereIn('conta_id', $dados['contas']);
            })
            ->where('status', 1)
            ->groupBy(['plano_conta_id', 'conta_id', 'data', 'tipo'])
            ->selectRaw("SUM(valor_pago) AS valor_pago, plano_conta_id, conta_id, DATE_FORMAT(data_pago, '{$date}') AS data, tipo")
            ->with(['planoConta', 'contaCaixa'])
            ->orderBy('data', "ASC")
        ->get();

        $entradas = [];       
        
        foreach($contasRecebidas as $values){
            if($values->planoConta){
                if(empty($entradas[$values->planoContaTrashed->codigo." - ".$values->planoContaTrashed->descricao][$values->data])){
                    $entradas[$values->planoContaTrashed->codigo." - ".$values->planoContaTrashed->descricao][$values->data] = $values->valor_pago;
                }else{
                    $entradas[$values->planoContaTrashed->codigo." - ".$values->planoContaTrashed->descricao][$values->data] += $values->valor_pago;
                }
            }else if($values->tipo == 'movimentacao') {
                if(empty($saidas["Movimentação entre contas"][$values->data])){
                    $saidas["Movimentação entre contas"][$values->data] = $values->valor_pago;
                }else{
                    $saidas["Movimentação entre contas"][$values->data] += $values->valor_pago;
                }
            }else{
                if(empty($entradas["sem plano de contas"][$values->data])){
                    $entradas["sem plano de contas"][$values->data] = $values->valor_pago;
                }else{
                    $entradas["sem plano de contas"][$values->data] += $values->valor_pago;
                }
            }

            if(empty($total[$values->data])){
                $total[$values->data] = $values->valor_pago;
            }else{
                $total[$values->data] += $values->valor_pago;
            }
        }

        $contasPagas = $instituicao->contasPagar()
            ->whereBetween('data_pago', $data)
            ->when($dados['contas'], function($q) use($dados){
                $q->whereIn('conta_id', $dados['contas']);
            })
            ->where('status', 1)
            ->groupBy(['plano_conta_id', 'conta_id', 'data', 'tipo'])
            ->selectRaw("SUM(valor_pago) AS valor_pago, plano_conta_id, conta_id, DATE_FORMAT(data_pago, '{$date}') AS data, tipo")
            ->with('planoConta')
            ->orderBy('data', "ASC")
        ->get();

        $saidas = [];

        foreach($contasPagas as $values){
            if($values->planoConta){
                $saidas[$values->planoContaTrashed->codigo." - ".$values->planoContaTrashed->descricao]['pai'] = $instituicao->planosContas->where('id', $values->planoConta->plano_conta_id)->first();
                
                if(empty($saidas[$values->planoContaTrashed->descricao][$values->data])){
                    $saidas[$values->planoContaTrashed->codigo." - ".$values->planoContaTrashed->descricao][$values->data] = $values->valor_pago;
                }else{
                    $saidas[$values->planoContaTrashed->codigo." - ".$values->planoContaTrashed->descricao][$values->data] += $values->valor_pago;
                }

                
            }else if($values->tipo == 'movimentacao') {
                if(empty($saidas["Movimentação entre contas"][$values->data])){
                    $saidas["Movimentação entre contas"][$values->data] = $values->valor_pago;
                }else{
                    $saidas["Movimentação entre contas"][$values->data] += $values->valor_pago;
                }
            }else{                
                if(empty($saidas["sem plano de contas"][$values->data])){
                    $saidas["sem plano de contas"][$values->data] = $values->valor_pago;
                }else{
                    $saidas["sem plano de contas"][$values->data] += $values->valor_pago;
                }
            }

            if(empty($total[$values->data])){
                $total[$values->data] = -$values->valor_pago;
            }else{
                $total[$values->data] -= $values->valor_pago;
            }
        }

        $cabecalho = array_unique(array_filter(array_column($contasRecebidas->toArray(),'data')) + array_filter(array_column($contasPagas->toArray(),'data')));

        sort($cabecalho, SORT_NUMERIC);
        ksort($entradas);
        ksort($saidas);

        return view('instituicao.relatorios.financeiro.fluxo_caixa.tabela', \compact('entradas', 'saidas', 'total', 'cabecalho'));
        
    }

    public function exportRelatorios(Request $request, Excel $excel, $relatorio, $tipo){
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $dados = $request->all();

        switch($relatorio){
            case 'recebidas':
                $dados = $instituicao->contasReceber()->relatorioContasReceber($dados)->where('status', 1)->get();
                $view = ($tipo == 'pdf') ? 'instituicao.relatorios.financeiro.pdf.tabela_recebidas' : 'instituicao.relatorios.financeiro.tabela_recebidas';
            break;
            case 'pagas':
                $dados = $instituicao->contasPagar()->relatorioContasPagar($dados)->where('status', 1)->get();
                $view = ($tipo == 'pdf') ? 'instituicao.relatorios.financeiro.pdf.tabela_pagas' : 'instituicao.relatorios.financeiro.tabela_pagas';
            break;
            case 'a_receber':
                $dados = $instituicao->contasReceber()->relatorioContasReceber($dados)->where('status', 0)->get();
                $view = ($tipo == 'pdf') ? 'instituicao.relatorios.financeiro.pdf.tabela_a_receber' : 'instituicao.relatorios.financeiro.tabela_a_receber';
            break;
            case 'a_pagar':
                $dados = $instituicao->contasPagar()->relatorioContasPagar($dados)->where('status', 0)->get();
                $view = ($tipo == 'pdf') ? 'instituicao.relatorios.financeiro.pdf.tabela_a_pagar' : 'instituicao.relatorios.financeiro.tabela_a_pagar';
            break;
        }

        if($tipo == 'pdf'){
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML(view($view, \compact('dados', 'instituicao' )))->setPaper('a4', 'landscape');;
            return $pdf->stream();
        }else{
            return $this->getExcel(
                new RelatoriosFinanceirosExport($dados, $view),
                'Contas Recebidas '.date("Y-m-d H:s:i").".".$tipo,
                $excel,
                $tipo
            );
        }
    }
}
