<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\Movimentacao\CriarMovimentacaoRequest;
use App\Http\Requests\Movimentacao\DuplicarMovimentacaoRequest;
use App\Instituicao;
use App\Movimentacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Movimentacoes extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_movimentacoes');

        return view('instituicao.movimentacoes.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_movimentacoes');
        $intituicao = Instituicao::find($request->session()->get('instituicao'));
        $contas = $intituicao->contas()->get();
        $tipo_movimentacao = Movimentacao::naturezas();
        return view('instituicao.movimentacoes.criar', \compact('contas', 'tipo_movimentacao'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarMovimentacaoRequest $request)
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
                'conta_id' => $movimentacao->contaOrigem->id,
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
                'conta_id' => $movimentacao->contaDestino->id,
                'valor_total' => $dados['valor']
            ];

            $contaReceber = $movimentacao->contaReceber()->create($dadosReceber);
            $contaReceber->criarLogCadastro($usuario_logado, $intituicao->id);
        });

        return redirect()->route('instituicao.movimentacoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Movimentação cadastrada com sucesso!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Movimentacao $movimentacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_movimentacoes');
        $intituicao_id = $request->session()->get('instituicao');

        abort_unless($movimentacao->instituicao_id === $intituicao_id, 403);

        DB::transaction(function() use($intituicao_id, $request, $movimentacao){
            $usuario_logado = $request->user('instituicao');

            $movimentacao->delete();
            $movimentacao->criarLogExclusao($usuario_logado, $intituicao_id);

            $movimentacao->contaPagar()->delete();
            $movimentacao->contaReceber()->delete();

        });

        return redirect()->route('instituicao.movimentacoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso',
            'text' => 'Movimentação excluida com sucesso!'
        ]);
    }

    public function duplicar(DuplicarMovimentacaoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'duplicar_movimentacoes');
        $intituicao = Instituicao::find($request->session()->get('instituicao'));
        $dados = $request->validated();

        DB::transaction(function() use($request, $dados, $intituicao){
            $usuario_logado = $request->user('instituicao');
            foreach ($dados['ids'] as $key => $value) {
                $movimentacao = Movimentacao::find($value);   
                
                if($movimentacao->instituicao_id == $intituicao->id){
                    $data = [
                        'tipo_movimentacao' => $movimentacao->tipo_movimentacao,
                        'data' => date('Y-m-d'),
                        'conta_id_origem' => $movimentacao->conta_id_origem,
                        'conta_id_destino' => $movimentacao->conta_id_destino,
                        'valor' => $movimentacao->valor,
                        'obs' => "movimentação duplicada - referente a conta com id {$movimentacao->id}",
                        'usuario_instituicao_id' => $usuario_logado->id,
                    ];

                    $novaMovimentacao = $intituicao->movimentacoes()->create($data);

                    $novaMovimentacao->criarLogCadastro($usuario_logado, $intituicao->id);

                    //conta a pagar
                    $dadosPagar = [
                        'tipo' => 'movimentacao',
                        'total' => $data['valor'],
                        'data_vencimento' => $data['data'],
                        'descricao' => "movimentação entre contas de {$novaMovimentacao->contaOrigem->nome} para {$novaMovimentacao->contaDestino->nome}",
                        'valor_parcela' => $data['valor'],
                        'instituicao_id' => $intituicao->id,
                        'num_parcela' => 1,
                        'status' => 1,
                        'data_pago' => $dados['data'],
                        'valor_pago' => $dados['valor'],
                        'conta_id' => $novaMovimentacao->contaOrigem->id
                    ];

                    $contaPagar = $novaMovimentacao->contaPagar()->create($dadosPagar);
                    $contaPagar->criarLogCadastro($usuario_logado, $intituicao->id);
                
                    //conta a receber
                    $dadosReceber = [
                        'tipo' => 'movimentacao',
                        'total' => $data['valor'],
                        'data_vencimento' => $data['data'],
                        'descricao' => "movimentação entre contas de {$novaMovimentacao->contaOrigem->nome} para {$novaMovimentacao->contaDestino->nome}",
                        'valor_parcela' => $data['valor'],
                        'instituicao_id' => $intituicao->id,
                        'num_parcela' => 1,
                        'status' => 1,
                        'data_pago' => $dados['data'],
                        'valor_pago' => $dados['valor'],
                        'conta_id' => $novaMovimentacao->contaDestino->id
                    ];

                    $contaReceber = $novaMovimentacao->contaReceber()->create($dadosReceber);
                    $contaReceber->criarLogCadastro($usuario_logado, $intituicao->id);
                }
            }
        });

        return response()->json([
            'header' => "Sucesso",
            'text' => "Movimentações duplicadas com sucesso",
            'icon' => "success",
        ]);
    }
}
