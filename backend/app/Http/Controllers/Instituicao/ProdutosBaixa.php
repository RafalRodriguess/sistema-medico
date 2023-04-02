<?php

namespace App\Http\Controllers\Instituicao;

use App\ProdutoBaixa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProdutosBaixa\CriarProdutosBaixaRequest;
use App\Http\Requests\ProdutosBaixa\EditarProdutosBaixaRequest;
use App\EstoqueBaixa;
use App\EstoqueEntradaProdutos;
use App\Instituicao;

use Illuminate\Support\Facades\DB;


class ProdutosBaixa extends Controller
{
    public $semEstoque;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, EstoqueBaixa $estoqueBaixa)
    {

        $this->authorize('habilidade_instituicao_sessao', 'visualizar_estoque_baixa_produtos');

        $instituicao_id = $request->session()->get('instituicao');

        abort_unless($estoqueBaixa->instituicao_id === $instituicao_id, 403);

        return view('instituicao.produtos_baixa/lista', \compact('estoqueBaixa'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, EstoqueBaixa $estoqueBaixa)
    {

        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_estoque_baixa_produtos');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));

        abort_unless($estoqueBaixa->instituicao_id === $instituicao->id, 403);

        $produtos = $instituicao->produtos()->get();


        return view('instituicao.produtos_baixa/criar', \compact('estoqueBaixa', 'produtos'));
    }

    public function store(CriarProdutosBaixaRequest $request, EstoqueBaixa $estoqueBaixa)
    {
        config(["auth.defaults.guard" => "instituicao"]);

        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_estoque_baixa_produtos');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));

        DB::transaction(function () use ($request, $instituicao, $estoqueBaixa) {
            $usuario_logado = $request->user('instituicao');
            $produto = $request->validated();
            $estoqueEntradaProdutos = EstoqueEntradaProdutos::where('id_produto', $produto['produto_id']);
            $BaixaProdutos = ProdutoBaixa::where('produto_id', $produto['produto_id']);

            if (intval($estoqueEntradaProdutos->sum('quantidade')) < intval($BaixaProdutos->sum('quantidade') + $produto['quantidade'])) {

                $this->semEstoque = true;
                $this->semEstoqueIdProduto[] = $produto['produto_id'];
            } else {

                $produto['baixa_id'] = $estoqueBaixa->id;
                $estoqueBaixaProdutos  = $estoqueBaixa->estoqueBaixaProdutos()->create($produto);

                $estoqueBaixaProdutos->criarLogCadastro(
                    $usuario_logado,
                    $instituicao->id
                );
            }

            return $estoqueBaixa;
        });

        if ($this->semEstoque) {

            return redirect()->route('instituicao.produtos_baixa.index', [$estoqueBaixa])->with('mensagem', [
                'icon' => 'error',
                'title' => 'Sem Estoque.',
                'text' => 'Produtos sem Estoque - ID - ' . implode(',', $this->semEstoqueIdProduto)
            ]);
        } else {

            return redirect()->route('instituicao.produtos_baixa.index', [$estoqueBaixa])->with('mensagem', [
                'icon' => 'success',
                'title' => 'Sucesso.',
                'text' => 'Inserido com sucesso!'
            ]);
        }
    }

    public function edit(Request $request, EstoqueBaixa $estoqueBaixa, ProdutoBaixa $produtoBaixa)
    {

        $this->authorize('habilidade_instituicao_sessao', 'editar_estoque_baixa_produtos');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($estoqueBaixa->instituicao_id === $instituicao->id, 403);

        $produtos =  $instituicao->produtos()->get();
        $estoqueBaixaProdutos = $instituicao->estoqueBaixa()->get();

        return view('instituicao.produtos_baixa/editar', \compact('produtoBaixa', 'estoqueBaixaProdutos', 'produtos', 'estoqueBaixa'));
    }

    public function update(EditarProdutosBaixaRequest $request, EstoqueBaixa $estoqueBaixa, ProdutoBaixa $produtoBaixa)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_estoque_baixa_produtos');

        $instituicao_id = $request->session()->get('instituicao');
        abort_unless($estoqueBaixa->instituicao_id === $instituicao_id, 403);
        abort_unless($estoqueBaixa->id === $produtoBaixa->baixa_id, 403);

        $estoqueEntradaProdutos = EstoqueEntradaProdutos::where('id_produto', $produtoBaixa['produto_id']);
        $BaixaProdutos = ProdutoBaixa::where('produto_id', $produtoBaixa['produto_id']);
        if (intval($estoqueEntradaProdutos->sum('quantidade')) < intval($BaixaProdutos->sum('quantidade')) + intval($request->quantidade)) {

            return redirect()->route('instituicao.produtos_baixa.index', [$estoqueBaixa])->with('mensagem', [
                'icon' => 'error',
                'title' => 'Erro ao Editar.',
                'text' => 'Sem Estoque'
            ]);
        }

        $dados = $request->validated();
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function () use ($request, $dados, $instituicao, $produtoBaixa) {
            $produtoBaixa->update($dados);
            $usuario_logado = $request->user('instituicao');

            $produtoBaixa->criarLogCadastro(
                $usuario_logado,
                $instituicao->id
            );

            return $produtoBaixa;
        });

        return redirect()->route('instituicao.produtos_baixa.index', [$estoqueBaixa])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Editado com sucesso!'
        ]);
    }


    public function destroy(Request $request, EstoqueBaixa $estoqueBaixa, ProdutoBaixa $produtoBaixa)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_estoque_baixa_produtos');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($estoqueBaixa->instituicao_id === $instituicao->id, 403);

        DB::transaction(function () use ($estoqueBaixa, $request, $instituicao, $produtoBaixa) {
            $produtoBaixa->delete();

            $usuario_logado = $request->user('instituicao');

            $estoqueBaixa->criarLogExclusao(
                $usuario_logado,
                $instituicao->id
            );

            return $estoqueBaixa;
        });

        return redirect()->route('instituicao.produtos_baixa.index', [$estoqueBaixa])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Excluído com sucesso!'
        ]);
    }
}
