<?php

namespace App\Http\Controllers\Instituicao;

use App\EstoqueBaixa;
use App\EstoqueEntradaProdutos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EstoqueBaixa\CriarEstoqueBaixaRequest;
use App\Http\Requests\EstoqueBaixa\EditarEstoqueBaixaRequest;
use App\EstoqueEntradas;
use App\Instituicao;

use Illuminate\Support\Facades\DB;


class EstoqueBaixaProdutos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, EstoqueBaixa $estoqueBaixa)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_estoque_baixa_produtos');

        return view('instituicao.estoque_baixa/lista', \compact('estoqueBaixa'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_estoque_baixa_produtos');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));

        $estoques =  $instituicao->estoques()->get();
        $setores_exame = $instituicao->setoresExame()->get();
        $motivos = $instituicao->motivoBaixa()->get();
        $produtos_selecionados = old('produtos', []);

        if (!empty($produtos_selecionados)) {
            $quantidades_produtos = [];
            collect($produtos_selecionados)->map(function ($item) use (&$quantidades_produtos) {
                if (!empty($item['id_entrada_produto']))
                    $quantidades_produtos[$item['id_entrada_produto']] = $item['quantidade'] ?? 0;
            });

            $produtos_selecionados = EstoqueEntradaProdutos::buscarProdutosEmEstoque('', null, collect($produtos_selecionados)->pluck('id_entrada_produto')->toArray())
                ->get()
                ->map(function ($item) use ($quantidades_produtos) {
                    $item->quantidade_selecionada = $quantidades_produtos[$item->id] ?? 0;
                    return $item;
                });
        }

        return view('instituicao.estoque_baixa/criar', \compact(
            'motivos',
            'setores_exame',
            'produtos',
            'estoques',
            'produtos_selecionados'
        ));
    }

    public function store(CriarEstoqueBaixaRequest $request)
    {
        config(["auth.defaults.guard" => "instituicao"]);

        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_estoque_baixa_produtos');
        DB::transaction(function () use ($request) {
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            $dados['usuario_id'] = $usuario_logado->id;
            $instituicao = Instituicao::find($request->session()->get("instituicao"));
            $estoqueBaixa = $instituicao->estoqueBaixa()->create($dados);
            $estoqueBaixa->criarLogCadastro(
                $usuario_logado,
                $instituicao->id
            );

            // Verificando se os ids das entradas pertencem a instituicao
            $produtos = collect($dados['produtos'] ?? []);
            $produtos = $produtos->filter(function ($produto_baixa) {
                // Filtrando por instituicao
                $entrada = EstoqueEntradaProdutos::buscarProdutosEmEstoque('', null, $produto_baixa['id_entrada_produto'])->first();

                // Filtrando por quantidade válida
                return !empty($entrada) && ($entrada->saldo_total ?? 0) >= ($produto_baixa['quantidade'] ?? 0);
            });

            $estoqueBaixa->overwrite($estoqueBaixa->estoqueBaixaProdutos(), $produtos->toArray(), function ($new, $index, $method) use ($usuario_logado, $instituicao) {
                if ($method == 'create') {
                    $new->criarLogCadastro(
                        $usuario_logado,
                        $instituicao->id
                    );
                } else {
                    $new->criarLogEdicao(
                        $usuario_logado,
                        $instituicao->id
                    );
                }
            });
        });

        return redirect()->route('instituicao.estoque_baixa_produtos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Inserido com sucesso!'
        ]);
    }

    public function edit(Request $request, EstoqueBaixa $estoqueBaixa)
    {

        $this->authorize('habilidade_instituicao_sessao', 'editar_estoque_baixa_produtos');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($estoqueBaixa->instituicao_id === $instituicao->id, 403);

        $estoques =  $instituicao->estoques()->get();
        $setores_exame = $instituicao->setoresExame()->get();
        $motivos = $instituicao->motivoBaixa()->get();
        $produtos_selecionados = old('produtos', $estoqueBaixa->estoqueBaixaProdutos()->get()->toArray());

        if (!empty($produtos_selecionados)) {
            $quantidades_produtos = [];
            collect($produtos_selecionados)->map(function ($item) use (&$quantidades_produtos) {
                if (!empty($item['id_entrada_produto']))
                    $quantidades_produtos[$item['id_entrada_produto']] = $item['quantidade'] ?? 0;
            });

            $produtos_selecionados = EstoqueEntradaProdutos::buscarProdutosEmEstoque('', $estoqueBaixa, collect($produtos_selecionados)->pluck('id_entrada_produto')->toArray())
                ->get()
                ->map(function ($item) use ($quantidades_produtos) {
                    $item->quantidade_selecionada = $quantidades_produtos[$item->id] ?? 0;
                    return $item;
                });
        }
        
        return view('instituicao.estoque_baixa/editar', \compact(
            'motivos',
            'setores_exame',
            'produtos',
            'estoques',
            'produtos_selecionados',
            'estoqueBaixa'
        ));
    }

    public function update(CriarEstoqueBaixaRequest $request, EstoqueBaixa $estoqueBaixa)
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_estoque_baixa_produtos');
        $instituicao_id = $request->session()->get('instituicao');
        abort_unless($estoqueBaixa->instituicao_id === $instituicao_id, 403);


        DB::transaction(function () use ($estoqueBaixa, $request) {
            $instituicao = Instituicao::find($request->session()->get("instituicao"));
            $dados = $request->validated();
            $estoqueBaixa->update($dados);
            $usuario_logado = $request->user('instituicao');

            $estoqueBaixa->criarLogEdicao(
                $usuario_logado,
                $instituicao->id
            );

            // Verificando se os ids das entradas pertencem a instituicao
            $produtos = collect($dados['produtos'] ?? []);
            $produtos = $produtos->filter(function ($produto_baixa) {
                // Filtrando por instituicao
                $entrada = EstoqueEntradaProdutos::buscarProdutosEmEstoque('', null, $produto_baixa['id_entrada_produto'])->first();

                // Filtrando por quantidade válida
                return !empty($entrada) && ($entrada->saldo_total ?? 0) >= ($produto_baixa['quantidade'] ?? 0);
            });

            $estoqueBaixa->overwrite($estoqueBaixa->estoqueBaixaProdutos(), $produtos->toArray(), function ($new, $index, $method) use ($usuario_logado, $instituicao) {
                if ($method == 'create') {
                    $new->criarLogCadastro(
                        $usuario_logado,
                        $instituicao->id
                    );
                } else {
                    $new->criarLogEdicao(
                        $usuario_logado,
                        $instituicao->id
                    );
                }
            });
        });

        return redirect()->route('instituicao.estoque_baixa_produtos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Editado com sucesso!'
        ]);
    }


    public function destroy(Request $request, EstoqueBaixa $estoqueBaixa)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_estoque_baixa_produtos');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($estoqueBaixa->instituicao_id === $instituicao->id, 403);

        DB::transaction(function () use ($estoqueBaixa, $request, $instituicao) {
            $estoqueBaixa->delete();

            $usuario_logado = $request->user('instituicao');

            $estoqueBaixa->criarLogExclusao(
                $usuario_logado,
                $instituicao->id
            );

            return $estoqueBaixa;
        });

        return redirect()->route('instituicao.estoque_baixa_produtos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Excluído com sucesso!'
        ]);
    }
}
