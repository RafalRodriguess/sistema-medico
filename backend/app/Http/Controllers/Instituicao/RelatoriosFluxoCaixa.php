<?php

namespace App\Http\Controllers\Instituicao;

use App\ContaReceber;
use App\Exports\FluxoCaixaExport;
use App\GruposProcedimentos;
use App\Http\Controllers\Controller;
use App\Http\Requests\Movimentacao\CriarMovimentacaoRequest;
use App\Instituicao;
use App\Movimentacao;
use App\Conta;
use App\ContaPagar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;

class RelatoriosFluxoCaixa extends Controller
{
    public function index(Request $request)
    {
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $conta_caixa = $instituicao->contas()->get();
        $formaPagamentos = ContaPagar::formas_pagamento();

        return view('instituicao.relatorios.fluxo_caixa.lista', \compact('conta_caixa', 'instituicao', 'formaPagamentos'));
    }

    public function tabela(Request $request)
    {
        $dados = $request->input();
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        
        $contasReceber = $instituicao->contasReceber()
            ->where('cancelar_parcela', "0")
            ->whereBetween('data_pago', [$dados['data_inicio'], $dados['data_fim']])
            ->when($dados['contas'], function($q) use($dados){
                $q->whereIn('conta_id', $dados['contas']);
            })
            ->when($dados['formaPagamento'], function($q) use($dados){
                $q->where('forma_pagamento', $dados['formaPagamento']);
            })
            ->when($dados['usuarios'][0] != 'todos', function($q) use($dados){
                $q->whereIn('usuario_baixou_id', $dados['usuarios']);
            })
            ->with(['contaCaixa', 'paciente', 'convenio', 'agendamentos' => function($q){
                $q->with(['agendamentoProcedimento' => function($q){
                    $q->with(['procedimentoInstituicaoConvenio' => function($q){
                        $q->with(['convenios', 'procedimentoInstituicao' => function($q){
                            $q->with(['procedimento'])->withTrashed();
                        }]);
                    }]);
                }]);
            }]) 
            ->orderBy('data_pago', 'ASC')           
        ->get();

        $contasPagar = $instituicao->ContasPagar()
            ->whereBetween('data_pago', [$dados['data_inicio'], $dados['data_fim']])
            ->when($dados['contas'], function($q) use($dados){
                $q->whereIn('conta_id', $dados['contas']);
            })
            ->when($dados['formaPagamento'], function($q) use($dados){
                $q->where('forma_pagamento', $dados['formaPagamento']);
            })
            ->when($dados['usuarios'][0] != 'todos', function($q) use($dados){
                $q->whereIn('usuario_baixou_id', $dados['usuarios']);
            })
            ->with([
                'contaCaixa',
                'paciente',
                'prestador',
                'fornecedor' => function ($q) {
                    $q->withTrashed();
                },
            ])
            ->orderBy('data_pago', 'ASC')    
        ->get();

        $formaPagamento['entradas'] = $instituicao->contasReceber()
            ->where('cancelar_parcela', "0")
            ->whereBetween('data_pago', [$dados['data_inicio'], $dados['data_fim']])
            ->when($dados['contas'], function($q) use($dados){
                $q->whereIn('conta_id', $dados['contas']);
            })
            ->when($dados['formaPagamento'], function($q) use($dados){
                $q->where('forma_pagamento', $dados['formaPagamento']);
            })
            ->when($dados['usuarios'][0] != 'todos', function($q) use($dados){
                $q->whereIn('usuario_baixou_id', $dados['usuarios']);
            })
            ->select("forma_pagamento")
            ->selectRaw("SUM(valor_pago) as valor") 
            ->groupBy("forma_pagamento")
        ->get();

        $formaPagamento['saidas'] = $instituicao->ContasPagar()
            ->whereBetween('data_pago', [$dados['data_inicio'], $dados['data_fim']])
            ->when($dados['contas'], function($q) use($dados){
                $q->whereIn('conta_id', $dados['contas']);
            })
            ->when($dados['formaPagamento'], function($q) use($dados){
                $q->where('forma_pagamento', $dados['formaPagamento']);
            })
            ->when($dados['usuarios'][0] != 'todos', function($q) use($dados){
                $q->whereIn('usuario_baixou_id', $dados['usuarios']);
            })
            ->select("forma_pagamento")
            ->selectRaw("SUM(valor_pago) as valor")  
            ->groupBy("forma_pagamento")
        ->get();

        $saldo_inicial = $instituicao->contas()->whereIn('id', $dados['contas'])->sum('saldo_inicial') 
        + $instituicao->contasReceber()->where('cancelar_parcela', "0")->whereDate('data_pago', "<", $dados['data_inicio'])
            ->when($dados['contas'], function($q) use($dados){
                $q->whereIn('conta_id', $dados['contas']);
            })
            ->when($dados['formaPagamento'], function($q) use($dados){
                $q->where('forma_pagamento', $dados['formaPagamento']);
            })
            ->when($dados['usuarios'][0] != 'todos', function($q) use($dados){
                $q->whereIn('usuario_baixou_id', $dados['usuarios']);
            })
            ->sum("valor_pago")
        - $instituicao->ContasPagar()->whereDate('data_pago', "<", $dados['data_inicio'])
            ->when($dados['contas'], function($q) use($dados){
                $q->whereIn('conta_id', $dados['contas']);
            })
            ->when($dados['formaPagamento'], function($q) use($dados){
                $q->where('forma_pagamento', $dados['formaPagamento']);
            })
            ->when($dados['usuarios'][0] != 'todos', function($q) use($dados){
                $q->whereIn('usuario_baixou_id', $dados['usuarios']);
            })
            ->sum("valor_pago");

        // $saldo_inicial = $instituicao->contas()->saldo();

        $conta_caixa = $instituicao->contas()->get();

        return view('instituicao.relatorios.fluxo_caixa.tabela', \compact('contasReceber', 'contasPagar', 'formaPagamento', 'saldo_inicial', 'dados', "conta_caixa"));
       
    }

    public function showMovimentacao(Request $request)
    {
        $dados = $request->input();
        // dd($dados);
        $conta_selecionada = $dados['contas'];
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $saldo = $instituicao->contasReceber()->where('conta_id', $dados['contas'])->sum("valor_pago") - $instituicao->ContasPagar()->where('conta_id', $dados['contas'])
        ->sum("valor_pago");

        $contas = $instituicao->contas()->get();
        $tipo_movimentacao = Movimentacao::naturezas();

        $valor = $request->input('valor');

        return view('instituicao.relatorios.fluxo_caixa.modalGeraMovimento', \compact('contas', 'dados', 'tipo_movimentacao', 'conta_selecionada', 'saldo', 'valor'));
    }

    public function salvarMovimentacao(CriarMovimentacaoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_movimentacoes');

        $intituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();

        $dados['valor'] = str_replace('.','',$dados['valor']);
        $dados['valor'] = str_replace(',','.',$dados['valor']);

        DB::transaction(function() use($intituicao, $request, $dados){
            $usuario_logado = $request->user('instituicao');
            $dados['usuario_instituicao_id'] = $usuario_logado->id;
            $movimentacao = $intituicao->movimentacoes()->create($dados);

            $movimentacao->criarLogCadastro($usuario_logado, $intituicao->id);

            //conta a pagar
            $dadosPagar = [
                'tipo' => 'movimentacao',
                'total' => $dados['valor'],
                'data_vencimento' => $dados['data'],
                'descricao' => "movimentação entre contas de {$movimentacao->contaOrigem->descricao} para {$movimentacao->contaDestino->descricao}",
                'valor_parcela' => $dados['valor'],
                'instituicao_id' => $intituicao->id,
                'num_parcela' => 1,
                'status' => 1,
                'data_pago' => $dados['data'],
                'valor_pago' => $dados['valor'],
                'conta_id' => $movimentacao->contaOrigem->id
            ];

            $contaPagar = $movimentacao->contaPagar()->create($dadosPagar);
            $contaPagar->criarLogCadastro($usuario_logado, $intituicao->id);
           
            //conta a receber
            $dadosReceber = [
                'tipo' => 'movimentacao',
                'total' => $dados['valor'],
                'data_vencimento' => $dados['data'],
                'descricao' => "movimentação entre contas de {$movimentacao->contaOrigem->descricao} para {$movimentacao->contaDestino->descricao}",
                'valor_parcela' => $dados['valor'],
                'instituicao_id' => $intituicao->id,
                'num_parcela' => 1,
                'status' => 1,
                'data_pago' => $dados['data'],
                'valor_pago' => $dados['valor'],
                'conta_id' => $movimentacao->contaDestino->id
            ];

            $contaReceber = $movimentacao->contaReceber()->create($dadosReceber);
            $contaReceber->criarLogCadastro($usuario_logado, $intituicao->id);
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Movimentação cadastrada com sucesso!'
        ]);

        // return view('instituicao.relatorios.fluxo_caixa.menssagem')->with('mensagem', [
        //     'icon' => 'success',
        //     'title' => 'Sucesso.',
        //     'text' => 'Movimentação cadastrada com sucesso!'
        // ]);
    }

    public function export(Request $request, Excel $excel){
        $dados = $request->input();
                
        $export = new FluxoCaixaExport($dados);

        return $excel->download($export, "Fluxo de caixa ".date('YmdHis').".xlsx");
    }

    public function altararCaixa(Request $request){
        $this->authorize('habilidade_instituicao_sessao', 'exportar_caixa');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $dados = $request->input();
        $conta_caixa = Conta::find($dados['conta_destino']);

        abort_unless($instituicao->id === $conta_caixa->instituicao_id, 403);

        
        DB::transaction(function() use($instituicao, $request, $dados, $conta_caixa){
            $usuario_logado = $request->user('instituicao');
            if(!empty($dados['contas'])){
                foreach($dados['contas'] as $item){
                    $conta_receber = ContaReceber::find($item);
                    $conta_origem = Conta::find($conta_receber->conta_id);
                    $conta_receber->update([
                        'conta_id' => $conta_caixa->id,
                        'obs' => trim($conta_receber->obs."
(".date("d/m/Y H:s:i")." Conta caixa alterada de caixa: #{$conta_origem->id} {$conta_origem->descricao} para caixa: #{$conta_caixa->id} {$conta_caixa->descricao})")
                    ]);
                    $conta_receber->criarLogEdicao($usuario_logado, $instituicao->id);
                }
            }
        });

        return response()->json([
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Contas receber alteradas com sucesso!'
        ]);
    }

    public function getUsuarios(Request $request){
        if($request->ajax()){
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $nome = ($request->input('q')) ? $request->input('q') : '';
            
            // dd($request->page);
            $procedimentos = $instituicao->instituicaoUsuarios()->where('nome', 'like', "%{$nome}%")->simplePaginate(100);
    
            $morePages=true;
            if (empty($procedimentos->nextPageUrl())){
                $morePages=false;
            }
    
            $results = array(
                "results" => $procedimentos->items(),
                "pagination" => array(
                    "more" => $morePages,
                )
            );
            // dd($pacientes->per_page());
            return response()->json($results);
        }

    }
}