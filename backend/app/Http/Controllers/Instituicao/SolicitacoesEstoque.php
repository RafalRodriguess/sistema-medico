<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\SolicitacoesEstoque\CreateSolicitacaoEstoque;
use Illuminate\Http\Request;
use App\{
    SolicitacaoEstoque,
    Instituicao,
    InstituicoesPrestadores,
    Agendamentos,
    Pessoa,
    Produto
};
use Illuminate\Support\Facades\DB;

class SolicitacoesEstoque extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_solicitacoes_estoque');
        return view('instituicao.solicitacoes-estoque/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_solicitacoes_estoque');
        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
        $estoques_destino = $estoques_origem = $instituicao->estoques()->get();
        $unidades_internacao = $instituicao->unidadesInternacoes()->get();
        $setores_exame = $instituicao->setoresExame()->get();
        // Preparando o array de produtos para ser inserido no ready (necessário pois descrição usa relação)
        $produtos = [];
        if(!empty(old('produtos'))) {
            foreach(old('produtos') as $produto_old) {
                $produto = Produto::where('id', $produto_old['produtos_id'] ?? 0)
                    ->with([
                        'classe',
                        'especie',
                        'unidade'
                    ])
                    ->first();
                if(!empty($produto)) {
                    $produto->quantidade = $produto_old['quantidade'] ?? 0;
                    array_push($produtos, $produto);
                }
            }
        }

        return view('instituicao.solicitacoes-estoque/criar', array_merge(\compact(
            'estoques_origem',
            'estoques_destino',
            'unidades_internacao',
            'setores_exame',
            'produtos'
        ), [
            'opcoes_destino' => SolicitacaoEstoque::opcoes_destino
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSolicitacaoEstoque $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_solicitacoes_estoque');
        DB::transaction(function () use ($request) {
            $dados = collect($request->validated());
            // Validando instituicao
            $usuario = $request->user('instituicao');
            $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
            abort_unless($instituicao->estoques()->findOrFail($dados->get('estoque_origem_id')), 403);
            // Criando solicitacao
            $solicitacao = $instituicao->solicitacoesEstoque()->create($dados->except('produtos')->toArray());
            $solicitacao->overwrite($solicitacao->solicitacaoEstoqueProdutos(), $dados->get('produtos'));
            $solicitacao->criarLogCadastro($usuario);
        });

        return redirect()->route('instituicao.solicitacoes-estoque.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Solicitação de estoque cadastrada com sucesso!'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, SolicitacaoEstoque $solicitacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_solicitacoes_estoque');
        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
        abort_unless($instituicao->id == $solicitacao->instituicoes_id, 403);

        $estoques_origem = $instituicao->estoques()->get();
        $estoques_destino = collect($estoques_origem)->where('id','!=', $solicitacao->id)->all();
        $unidades_internacao = $instituicao->unidadesInternacoes()->get();
        $setores_exame = $instituicao->setoresExame()->get();
        // Preparando o array de produtos para ser inserido no ready (necessário pois descrição usa relação)
        $produtos = [];
        if(!empty(old('produtos'))) {
            if(!empty(old('produtos'))) {
                foreach(old('produtos') as $produto_old) {
                    $produto = Produto::where('id', $produto_old['produtos_id'] ?? 0)
                        ->with([
                            'classe',
                            'especie',
                            'unidade'
                        ])
                        ->first();
                    if(!empty($produto)) {
                        $produto->quantidade = $produto_old['quantidade'] ?? 0;
                        array_push($produtos, $produto);
                    }
                }
            }
        } else {
            $produtos = collect($solicitacao->solicitacaoEstoqueProdutos()->get())
                ->map(function($item) {
                    $produto = Produto::where('id', $item->produtos_id)
                        ->with([
                            'classe',
                            'especie',
                            'unidade'
                        ])
                        ->first();
                    if(!empty($produto)) {
                        $produto->quantidade = $item->quantidade;
                        return $produto;
                    }
                });
        }

        return view('instituicao.solicitacoes-estoque/editar', array_merge(\compact(
            'estoques_origem',
            'estoques_destino',
            'solicitacao',
            'unidades_internacao',
            'setores_exame',
            'produtos'
        ), [
            'opcoes_destino' => SolicitacaoEstoque::opcoes_destino
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateSolicitacaoEstoque $request, SolicitacaoEstoque $solicitacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_solicitacoes_estoque');
        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));

        // Validar instituicão da solicitação
        abort_unless($instituicao->solicitacoesEstoque()->find($solicitacao->id)->exists(), '403');

        DB::transaction(function () use ($request, $solicitacao, $instituicao) {
            $dados = collect($request->validated());
            // Criando solicitacao
            $usuario = $request->user('instituicao');
            $solicitacao->update($dados->except('produtos')->toArray());
            $solicitacao->overwrite($solicitacao->solicitacaoEstoqueProdutos(), $dados->get('produtos'));
            $solicitacao->criarLogEdicao($usuario);
        });

        return redirect()->route('instituicao.solicitacoes-estoque.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Solicitação de estoque atualizada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, SolicitacaoEstoque $solicitacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_solicitacoes_estoque');

        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
        abort_unless($instituicao->id == $solicitacao->instituicoes_id, 403);

        DB::transaction(function () use ($request, $solicitacao, $instituicao) {
            // Validando instituicao
            $usuario = $request->user('instituicao');
            $estoque = $instituicao->estoques()->findOrFail($solicitacao->estoque_origem_id);
            abort_unless($estoque->solicitacoesEstoque()->find($solicitacao->id)->exists(), '403');
            // Criando solicitacao
            $solicitacao->delete();
            $solicitacao->criarLogExclusao($usuario);
        });

        return redirect()->route('instituicao.solicitacoes-estoque.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Solicitação de estoque excluída com sucesso!'
        ]);
    }

    public function solicitacaoConsultorio(Request $request, Agendamentos $agendamento, Pessoa $paciente){
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_solicitacoes_estoque');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $usuario = $request->user('instituicao');
        // $estoques_destino = $estoques_origem = $instituicao->estoques()->get();
        $estoques = $instituicao->estoques()->get();
        $unidades_internacao = $instituicao->unidadesInternacoes()->get();
        $setores_exame = $instituicao->setoresExame()->get();
        // Preparando o array de produtos para ser inserido no ready (necessário pois descrição usa relação)
        $produtos = [];
        if(!empty(old('produtos'))) {
            foreach(old('produtos') as $produto_old) {
                $produto = Produto::where('id', $produto_old['produtos_id'] ?? 0)
                    ->with([
                        'classe',
                        'especie',
                        'unidade'
                    ])
                    ->first();
                if(!empty($produto)) {
                    $produto->quantidade = $produto_old['quantidade'] ?? 0;
                    array_push($produtos, $produto);
                }
            }
        }

        return view('instituicao.prontuarios.solicitacao-estoque.info', array_merge(\compact(
            'estoques',
            // 'estoques_destino',
            'unidades_internacao',
            'setores_exame',
            'produtos',
            'agendamento',
            'paciente',
            'usuario'
        ), [
            'opcoes_destino' => SolicitacaoEstoque::opcoes_destino
        ]));
    }

    public function solicitacaoConsultorioSalvar(CreateSolicitacaoEstoque $request, Agendamentos $agendamento, Pessoa $paciente){
        
        if($request->input("solicitacao_id")){
            $this->authorize('habilidade_instituicao_sessao', 'editar_solicitacoes_estoque');
            $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));

            $solicitacao = $instituicao->solicitacoesEstoque()->find($request->input("solicitacao_id"));            
    
            DB::transaction(function () use ($request, $solicitacao, $instituicao) {
                $dados = collect($request->validated());
                // Criando solicitacao
                $usuario = $request->user('instituicao');
                $solicitacao->update($dados->except('produtos')->toArray());
                $solicitacao->overwrite($solicitacao->solicitacaoEstoqueProdutos(), $dados->get('produtos'));
                $solicitacao->criarLogEdicao($usuario);
            });

            return $solicitacao;
    
        }else{
            $this->authorize('habilidade_instituicao_sessao', 'cadastrar_solicitacoes_estoque');
            $solicitacao = DB::transaction(function () use ($request) {
                $dados = collect($request->validated());
                // Validando instituicao
                $usuario = $request->user('instituicao');
                $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
                abort_unless($instituicao->estoques()->findOrFail($dados->get('estoque_origem_id')), 403);
                // Criando solicitacao
                $solicitacao = $instituicao->solicitacoesEstoque()->create($dados->except('produtos')->toArray());
                $solicitacao->overwrite($solicitacao->solicitacaoEstoqueProdutos(), $dados->get('produtos'));
                $solicitacao->criarLogCadastro($usuario);

                return $solicitacao;
            });
    
            return $solicitacao;
        }
    }

    public function solicitacaoConsultorioHistorico(Request $request, Agendamentos $agendamento, Pessoa $paciente){
        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));

        $solicitacoes = $instituicao->solicitacoesEstoque()->whereNotNull('agendamento_atendimentos_id')->whereHas('atendimento', function($q) use($paciente){
            $q->where('pessoa_id', $paciente->id);
        })->get();

        // $solicitacoes = $instituicao->solicitacoesEstoque()->where('destino', 1)->whereHas('atendimento', function($q) use($paciente){
        //     $q->where('pessoa_id', $paciente->id);
        // });

        return view('instituicao.prontuarios.solicitacao-estoque.historico', compact('solicitacoes'));

    }

    public function getSolicitacao(Request $request, SolicitacaoEstoque $solicitacao){
        $solicitacao = $solicitacao->where('id', $solicitacao->id)
            ->with(
                'atendimento.pessoa',
                'atendimento.agendamento',
                'solicitacaoEstoqueProdutos.produto.classe',
                'solicitacaoEstoqueProdutos.produto.unidade'             
            )->first();
        
        return response()->json($solicitacao);

    }

    public function deleteSolicitacao(Request $request, SolicitacaoEstoque $solicitacao){
        $this->authorize('habilidade_instituicao_sessao', 'excluir_solicitacoes_estoque');

        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
        abort_unless($instituicao->id == $solicitacao->instituicoes_id, 403);

        DB::transaction(function () use ($request, $solicitacao, $instituicao) {
            // Validando instituicao
            $usuario = $request->user('instituicao');
            $estoque = $instituicao->estoques()->findOrFail($solicitacao->estoque_origem_id);
            abort_unless($estoque->solicitacoesEstoque()->find($solicitacao->id)->exists(), '403');
            // Criando solicitacao
            $solicitacao->delete();
            $solicitacao->criarLogExclusao($usuario);
        });

        return response()->json(true);
    }
}
