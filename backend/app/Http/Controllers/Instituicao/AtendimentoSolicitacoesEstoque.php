<?php

namespace App\Http\Controllers\Instituicao;

use App\Casts\Checkbox;
use App\EstoqueBaixa;
use App\EstoqueEntradaProdutos;
use App\Http\Controllers\Controller;
use App\Http\Requests\SolicitacoesEstoque\AtendimentoSolicitacoesEstoqueRequest;
use App\Instituicao;
use App\SolicitacaoEstoque;
use App\SolicitacaoEstoqueProduto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AtendimentoSolicitacoesEstoque extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, SolicitacaoEstoque $solicitacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'atender_solicitacoes_estoque');

        $produtos = $solicitacao->solicitacaoEstoqueProdutos()->with([
            'produto',
            'produto.unidade',
            'motivoDivergencia'
        ])->get();
        $opcoes_destino = SolicitacaoEstoque::opcoes_destino;
        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
        $entradas_atendidas = $solicitacao->produtosAtendidos()->get();
        $produtos_disponiveis = [];
        EstoqueEntradaProdutos::buscarProdutosEmEstoque('', $solicitacao->baixaEstoque()->first(), $entradas_atendidas->pluck('id_entrada_produto')->toArray())
            ->get()
            ->map(function ($entrada) use (&$produtos_disponiveis) {
                $produtos_disponiveis[$entrada->id] = $entrada;
            });
        $produtos_atendidos = $entradas_atendidas->map(function($atendido) use ($produtos_disponiveis) {
                $atendido->entradaProduto = $produtos_disponiveis[$atendido->entradaProduto->id];
                return $atendido;
            });
        $motivos_divergencia = $instituicao->motivosDivergencia()->get();

        return view('instituicao.solicitacoes-estoque/atender', \compact(
            'solicitacao',
            'opcoes_destino',
            'motivos_divergencia',
            'produtos',
            'produtos_atendidos'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AtendimentoSolicitacoesEstoqueRequest $request, SolicitacaoEstoque $solicitacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'atender_solicitacoes_estoque');

        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));

        // Verificando se as entradas de produto são da mesma instituição que o usuário logado
        abort_unless($solicitacao->instituicoes_id == $instituicao->id, 403);

        DB::transaction(function () use ($solicitacao, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $dados = collect($request->validated());
            // Validando o motivo de baixa
            $motivo_baixa = SolicitacaoEstoque::buscarMotivoSolicitacaoEstoque();
            if (empty($motivo_baixa)) {
                return redirect()->back()->with('mensagem', [
                    'icon' => 'error',
                    'title' => 'Erro.',
                    'text' => 'Verifique se o motivo de baixa de estoque foi cadastrado para o atendimento de solicitação de estoque'
                ]);
            }


            // Buscando e atualizando produtos solicitados na solicitação
            $atendidos = true;
            array_map(function ($item) use (&$atendidos) {
                $produto = SolicitacaoEstoqueProduto::find($item['id']);
                unset($item['id']);
                $atendidos &= !empty($item['confirma_item'] ?? null);
                $produto->update($item);
                return $produto;
            }, $dados->get('produtos', []));

            // Buscando dados dos produtos selecionados e criando um dicionario
            $produtos_recebidos = collect($dados->get('produtos_recebidos', []));
            $produtos_disponiveis = [];
            EstoqueEntradaProdutos::buscarProdutosEmEstoque('', $solicitacao->baixaEstoque()->first(), $produtos_recebidos->pluck('id_entrada_produto')->toArray())
                ->get()
                ->map(function ($entrada) use (&$produtos_disponiveis) {
                    $produtos_disponiveis[$entrada->id] = $entrada;
                });

            // Validando as quantidades escolhidas pelo usuario e inserindo os valores já corrigidos
            $produtos_recebidos->filter(function ($atendido) use ($produtos_disponiveis) {
                // Removendo produtos com entradas inválidas ou quantidades inválidas
                $entrada = $produtos_disponiveis[$atendido['id_entrada_produto']] ?? null;
                return !empty($entrada) && ($atendido['quantidade'] ?? 0) > 0 && $atendido['quantidade'] <= $entrada->saldo_total;
            })->map(function ($atendido) use ($solicitacao) {
                // Convertendo o formato
                return [
                    'solicitacoes_estoque_id' => $solicitacao->id,
                    'quantidade' => $atendido['quantidade'],
                    'codigo_de_barras' => $atendido['codigo_de_barras'] ?? '',
                    'id_entrada_produto' => $atendido['id_entrada_produto']
                ];
            });
            $solicitacao->overwrite($solicitacao->produtosAtendidos(), $produtos_recebidos->toArray(), function ($new, $index, $method) use ($usuario_logado, $instituicao) {
                if ($method == 'create') {
                    $new->criarLogInstituicaoCadastro($usuario_logado, $instituicao->id);
                } else {
                    $new->criarLogInstituicaoEdicao($usuario_logado, $instituicao->id);
                }
            });

            // Criando ou atualizando a baixa de estoque relativa à solicitação de estoque (TEM UM BUG MALUCO COM CREATE NA SOLICITACAO)
            if (empty($solicitacao->baixaEstoque()->first())) {
                $baixa_estoque = EstoqueBaixa::create([
                    'estoque_id' => $solicitacao->estoque_origem_id,
                    'motivo_baixa_id' => $motivo_baixa->id,
                    'data_emissao' => \date('Y-m-d'),
                    'data_hora_baixa' => \date('H:i:s'),
                    'usuario_id' => $usuario_logado->id,
                    'instituicao_id' => $solicitacao->instituicoes_id
                ]);
                $solicitacao->update(['estoque_baixa_id' => $baixa_estoque->id]);
            } else {
                $solicitacao->baixaEstoque()->first()->update([
                    'data_emissao' => \date('Y-m-d'),
                    'data_hora_baixa' => \date('H:i:s'),
                    'usuario_id' => $usuario_logado->id,
                ]);
                $baixa_estoque = $solicitacao->baixaEstoque()->first();
            }

            // Limpando e cadastrando os novos produtos baixados
            $baixa_estoque->overwrite($baixa_estoque->estoqueBaixaProdutos(), $produtos_recebidos->toArray(), function ($new, $index, $method) use ($usuario_logado, $instituicao) {
                if ($method == 'create') {
                    $new->criarLogCadastro($usuario_logado, $instituicao->id);
                } else {
                    $new->criarLogEdicao($usuario_logado, $instituicao->id);
                }
            });

            // Validando se foi totalmente atendida ou não
            $solicitacao->update([
                'atendida' => $atendidos,
                'data_atendimento' => $atendidos ? \date('Y-m-d H:i:s') : null,
            ]);
        });

        return redirect()->route('instituicao.solicitacoes-estoque.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Atendimento de solicitação de estoque atualizado com sucesso!'
        ]);
    }

    public function imprimir(Request $request, SolicitacaoEstoque $solicitacao)
    {
        $produtos = $solicitacao->solicitacaoEstoqueProdutos()->with([
            'produto',
            'produto.unidade',
            'motivoDivergencia'
        ])->get();
        $opcoes_destino = SolicitacaoEstoque::opcoes_destino;
        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
        $entradas_atendidas = $solicitacao->produtosAtendidos()->get();
        $produtos_disponiveis = [];
        EstoqueEntradaProdutos::buscarProdutosEmEstoque('', $solicitacao->baixaEstoque()->first(), $entradas_atendidas->pluck('id_entrada_produto')->toArray())
            ->get()
            ->map(function ($entrada) use (&$produtos_disponiveis) {
                $produtos_disponiveis[$entrada->id] = $entrada;
            });
        $produtos_atendidos = $entradas_atendidas->map(function($atendido) use ($produtos_disponiveis) {
                $atendido->entradaProduto = $produtos_disponiveis[$atendido->entradaProduto->id];
                return $atendido;
            });
        $motivos_divergencia = $instituicao->motivosDivergencia()->get();
        return view('instituicao.solicitacoes-estoque/imprimir', \compact(
            'solicitacao',
            'opcoes_destino',
            'motivos_divergencia',
            'produtos',
            'produtos_atendidos'
        ));
    }
}
