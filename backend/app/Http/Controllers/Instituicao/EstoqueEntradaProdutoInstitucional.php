<?php

namespace App\Http\Controllers\Instituicao;

use App\EstoqueBaixa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EstoqueEntradaProdutos\EditarEstoqueEntradaProdutosRequest;
use App\Http\Requests\EstoqueEntradaProdutos\CriarEstoqueEntradaProdutosRequest;
use App\EstoqueEntradaProdutos;
use App\EstoqueEntradas;
use App\Http\Requests\EstoqueEntradaProdutos\BuscarEntradaProdutos;
use App\Instituicao;
use Illuminate\Support\Facades\DB;

class EstoqueEntradaProdutoInstitucional extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, EstoqueEntradas $entradaEstoque)
    {

        $this->authorize('habilidade_instituicao_sessao', 'visualizar_estoque_entrada_produtos');
        $instituicao_id = $request->session()->get('instituicao');

        abort_unless($entradaEstoque->instituicao_id === $instituicao_id, 403);

        return view('instituicao.estoque_entrada_produtos/lista', \compact('entradaEstoque'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, EstoqueEntradas $entradaEstoque)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_estoque_entrada_produtos');
        $instituicao_id = $request->session()->get('instituicao');

        abort_unless($entradaEstoque->instituicao_id === $instituicao_id, 403);

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $produtos = $instituicao->produtos()->get();

        return view('instituicao.estoque_entrada_produtos/criar', compact('entradaEstoque', 'produtos'));
    }

    public function store(CriarEstoqueEntradaProdutosRequest $request, EstoqueEntradas $entradaEstoque)
    {

        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_estoque_entrada_produtos');
        $instituicao_id = $request->session()->get('instituicao');

        abort_unless($entradaEstoque->instituicao_id === $instituicao_id, 403);

        $dados = $request->validated();
        $dados['valor'] = floatvalue($dados['valor']);
        $dados['valor_custo'] = floatvalue($dados['valor_custo']);

        DB::transaction(function () use ($request, $instituicao_id, $entradaEstoque, $dados) {
            $estoqueEntradasProdutos = $entradaEstoque->estoqueEntradaProdutos()->create($dados);

            $usuario_logado = $request->user('instituicao');

            $estoqueEntradasProdutos->criarLogCadastro(
                $usuario_logado,
                $instituicao_id
            );


            return $estoqueEntradasProdutos;
        });

        return redirect()->route('instituicao.estoque_entrada_produtos.index', [$entradaEstoque])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Inserido com sucesso!'
        ]);
    }


    public function edit(Request $request, EstoqueEntradas $entradaEstoque, EstoqueEntradaProdutos $estoqueEntradaProduto)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_estoque_entrada_produtos');
        $instituicao_id = session()->get('instituicao');

        abort_unless($entradaEstoque->instituicao_id === $instituicao_id, 403);
        abort_unless($entradaEstoque->id === $estoqueEntradaProduto->id_entrada, 403);

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $produtos = $instituicao->produtos()->get();

        return view('instituicao.estoque_entrada_produtos/editar', \compact('entradaEstoque', 'produtos', 'estoqueEntradaProduto'));
    }

    public function update(EditarEstoqueEntradaProdutosRequest $request, EstoqueEntradas $entradaEstoque, EstoqueEntradaProdutos $estoqueEntradaProduto)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_estoque_entrada_produtos');
        $instituicao_id = $request->session()->get('instituicao');

        abort_unless($entradaEstoque->instituicao_id === $instituicao_id, 403);
        abort_unless($entradaEstoque->id === $estoqueEntradaProduto->id_entrada, 403);

        $dados = $request->validated();
        $dados['valor'] = floatvalue($dados['valor'] ?? null);
        $dados['valor_custo'] = floatvalue($dados['valor_custo'] ?? null);

        $instituicao = Instituicao::find($request->session()->get("instituicao"));

        DB::transaction(function () use ($estoqueEntradaProduto, $instituicao, $request, $dados) {
            $estoqueEntradaProduto->update($dados);

            $usuario_logado = $request->user('instituicao');

            $estoqueEntradaProduto->criarLogEdicao(
                $usuario_logado,
                $instituicao->id
            );

            return $estoqueEntradaProduto;
        });

        return redirect()->route('instituicao.estoque_entrada_produtos.index', [$entradaEstoque])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Editado com sucesso!'
        ]);
    }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\EstoqueEntradaProdutos  $EstoqueEntradaProdutos
    //  * @return \Illuminate\Http\Response
    //  */
    public function destroy(Request $request, EstoqueEntradas $entradaEstoque, EstoqueEntradaProdutos $estoqueEntradaProduto)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_estoque_entrada_produtos');
        $instituicao = $request->session()->get('instituicao');

        abort_unless($entradaEstoque->instituicao_id === $instituicao, 403);
        abort_unless($entradaEstoque->id === $estoqueEntradaProduto->id_entrada, 403);

        DB::transaction(function () use ($estoqueEntradaProduto, $request, $instituicao) {
            $estoqueEntradaProduto->delete();

            $usuario_logado = $request->user('instituicao');

            $estoqueEntradaProduto->criarLogExclusao(
                $usuario_logado,
                $instituicao
            );

            return $estoqueEntradaProduto;
        });

        return redirect()->route('instituicao.estoque_entrada_produtos.index', [$entradaEstoque])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Excluído com sucesso!'
        ]);
    }
}
