<?php

namespace App\Http\Controllers\Instituicao;

use App\Instituicao;
use App\ContaPagar;
use App\Http\Controllers\Controller;
use App\Http\Requests\DemonstrativoFinanceiro\PesquisaDemonstrativoFinanceiroRequest;
use App\Http\Requests\DemonstrativosFinanceiros\DemonstrativoFinanceiroRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DemonstrativosFinanceiro extends Controller
{
    public function index(Request $request)
    {
        
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_relatorios_demonstrativo_financeiro');
        
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $contas = $instituicao->contas()->get();
        $formaPagamentos = ContaPagar::formas_pagamento();
        $planosConta = $instituicao->planosContas()->get();
        //$formasRecebimento = $instituicao->formasRecebimento()->get();

        return view('instituicao.relatorios.demonstrativo-financeiro.lista', \compact('contas', 'formaPagamentos', 'planosConta', 'instituicao'));
    }

    public function tabela(DemonstrativoFinanceiroRequest $request)
    {
        $dados = $request->validated();

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $receber = $instituicao->contasReceber()->searchDemonstrativo($dados);
        $a_receber = $instituicao->contasReceber()->searchDemonstrativo($dados)->sum('valor_parcela');
        $recebidos = $instituicao->contasReceber()->searchDemonstrativo($dados)->where('status', 1)->sum('valor_pago');

        $pagar = $instituicao->contasPagar()->searchDemonstrativo($dados);
        $a_pagar = $instituicao->contasPagar()->searchDemonstrativo($dados)->sum('valor_parcela');
        $pagos = $instituicao->contasPagar()->searchDemonstrativo($dados)->where('status', 1)->sum('valor_pago');

        if($dados['tipo_pesquisa'] != "bancaria"){
            $union = $receber->union($pagar)->orderBy($dados['tipo_pesquisa'])->get();
        }else{
            $union = $receber->union($pagar)->orderBy(DB::raw('IF(`data_compensacao` IS NOT NULL, `data_compensacao`, `data_pago`)'))->get();
        }            
        
        $total_previsto = $a_receber - $a_pagar;
        $total = $recebidos - $pagos;

        if($dados['tipo_relatorio'] == "detalhado"){
            return view('instituicao.relatorios.demonstrativo-financeiro.tabela', \compact('union', 'recebidos', 'pagos', 'total', 'total_previsto', 'a_receber', 'a_pagar', 'dados'));
        }else if($dados['tipo_relatorio'] == "acamulativo"){
            $saldo_pagar = $instituicao->contasPagar()->searchDemonstrativoSum($dados)->first();
            $saldo_receber = $instituicao->contasReceber()->searchDemonstrativoSum($dados)->first();
            $saldo_anterior = $saldo_receber->valor - $saldo_pagar->valor;

            return view('instituicao.relatorios.demonstrativo-financeiro.tabela_acumulativo', \compact('union', 'recebidos', 'pagos', 'total', 'total_previsto', 'a_receber', 'a_pagar', 'dados', 'saldo_anterior'));
        }else if($dados['tipo_relatorio'] == "caixa_resumido"){
            $caixa = [];
            
            foreach($union as $item){                
                if(!empty($caixa[$item->contaCaixa->descricao])){
                    $caixa[$item->contaCaixa->descricao]['entradas'] += ($item->natureza == "conta_receber") ? $item->valor : 0;
                    $caixa[$item->contaCaixa->descricao]['saidas'] += ($item->natureza == "conta_pagar") ? $item->valor : 0;
                }else{
                    $caixa[$item->contaCaixa->descricao]['entradas'] = ($item->natureza == "conta_receber") ? $item->valor : 0;
                    $caixa[$item->contaCaixa->descricao]['saidas'] = ($item->natureza == "conta_pagar") ? $item->valor : 0;
                }                
            }

            return view('instituicao.relatorios.demonstrativo-financeiro.tabela_acumulativo_caixa', \compact('caixa'));
        }else if($dados['tipo_relatorio'] == "plano_contas"){
            $planoContas = [];
            foreach($union as $item){    
                if(!empty($item->planoConta->codigo)){
                    if(!empty($planoContas[$item->planoConta->codigo])){
                        $planoContas[$item->planoConta->codigo]['valor_pago'] += $item->valor;
                        $planoContas[$item->planoConta->codigo]['valor_parcela'] += $item->valor_parcela;
                    }else{
                        $planoContas[$item->planoConta->codigo]['descricao'] = $item->planoConta->descricao;
                        $planoContas[$item->planoConta->codigo]['valor_pago'] = $item->valor;
                        $planoContas[$item->planoConta->codigo]['valor_parcela'] = $item->valor_parcela;
                        $planoContas[$item->planoConta->codigo]['padrao'] = $item->planoConta->padrao;
                    }
                }else{
                    if(!empty($planoContas[0])){
                        $planoContas[0]['valor_pago'] += $item->valor;
                        $planoContas[0]['valor_parcela'] += $item->valor_parcela;
                    }else{
                        $planoContas[0]['descricao'] = "Sem plano de conta";
                        $planoContas[0]['valor_pago'] = $item->valor;
                        $planoContas[0]['valor_parcela'] = $item->valor_parcela;
                        $planoContas[0]['padrao'] = '';
                    }
                }
            }
            ksort($planoContas);
            // dd($planoContas);

            return view('instituicao.relatorios.demonstrativo-financeiro.tabela_plano_contas', \compact('planoContas'));
 
        }else{
            return view('instituicao.relatorios.demonstrativo-financeiro.tabela_resumida', \compact('union', 'recebidos', 'pagos', 'total', 'total_previsto', 'a_receber', 'a_pagar', 'dados'));
        }
        
    }
}