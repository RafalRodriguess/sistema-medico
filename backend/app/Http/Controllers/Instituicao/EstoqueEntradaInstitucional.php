<?php

namespace App\Http\Controllers\Instituicao;

use App\EstoqueBaixa;
use App\EstoqueEntradaProdutos;
use App\Http\Requests\EntradasEstoque\BuscarLoteRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EstoqueEntrada\CriarEstoqueEntradaRequest;
use App\Http\Requests\EstoqueEntrada\EditarEstoqueEntradaRequest;
use App\EstoqueEntradas;
use App\Http\Requests\EstoqueEntradaProdutos\BuscarEntradaProdutos;
use App\Instituicao;
use App\Produto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;


class EstoqueEntradaInstitucional extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_estoque_entrada');

        return view('instituicao.estoque_entrada/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_estoque_entrada');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));

        $estoqueEntradas = $instituicao->estoqueEntrada();
        $tiposDocumentos =  $instituicao->tiposDocumentos()->get();
        $estoques =  $instituicao->estoques()->get();
        $fornecedores = $instituicao->fornecedores()->get();
        $produtos = [];
        if (!empty(old('produtos'))) {
            $produtos = collect(old('produtos'))->map(function($item) {
                $produto = Produto::find($item['id'] ?? -1);
                if (!empty($produto)) {
                    $unidade = $produto->unidade()->first();
                    $item['descricao'] = $produto->descricao;
                    $item['unidade'] = !empty($unidade) ? $unidade->descricao : '';
                    return $item;
                }
            })->toArray();
        }
        return view('instituicao.estoque_entrada/criar', \compact('produtos', 'estoqueEntradas', 'tiposDocumentos', 'estoques', 'fornecedores'));
    }

    public function store(CriarEstoqueEntradaRequest $request)
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_estoque_entrada');

        DB::transaction(function () use ($request, $instituicao) {
            $dados = collect($request->validated());
            $entradaEstoque = $instituicao->estoqueEntrada()->create($dados->except('produtos')->toArray());
            $produtos = collect($request->validated()['produtos'])
                ->filter(function ($produtos) {
                    return !is_null($produtos['id']);
                })
                ->map(function ($produtos) {
                    return [
                        'id_produto' => $produtos['id'],
                        'quantidade' => $produtos['quantidade'],
                        'lote' => $produtos['lote'],
                        'valor' => floatvalue($produtos['valor']),
                        'valor_custo' => floatvalue($produtos['valor_custo']),
                        'validade' => $produtos['validade']
                    ];
                });

            $usuario_logado = $request->user('instituicao');

            $entradaEstoque->criarLogCadastro(
                $usuario_logado,
                $instituicao->id
            );

            foreach ($produtos as $produto) {

                $produto['id_entrada'] = $entradaEstoque->id;
                $EstoreEntradaProdutos  = $entradaEstoque->estoqueEntradaProdutos()->create($produto);

                $EstoreEntradaProdutos->criarLogCadastro(
                    $usuario_logado,
                    $instituicao->id
                );
            }
            return $entradaEstoque;
        });

        return redirect()->route('instituicao.estoque_entrada.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Inserido com sucesso!'
        ]);
    }

    public function edit(Request $request, EstoqueEntradas $entradaEstoque)
    {

        $this->authorize('habilidade_instituicao_sessao', 'editar_estoque_entrada');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($entradaEstoque->instituicao_id === $instituicao->id, 403);

        $tiposDocumentos =  $instituicao->tiposDocumentos()->get();
        $estoques =  $instituicao->estoques()->get();
        $estoqueEntradaProdutos = $entradaEstoque->estoqueEntradaProdutos()->get();
        $fornecedores = $instituicao->fornecedores()->get();
        $produtos = [];
        if (!empty(old('produtos'))) {
            foreach (old('produtos') as $key => $item) {
                $item = collect($item);
                $produto = Produto::find($item->get('id', -1));
                if (!empty($produto)) {
                    $unidade = $produto->unidade()->first();
                    $item->set('descricao', $produto->descricao);
                    $item->set('unidade', !empty($unidade) ? $unidade->descricao : '');
                }
                array_push($produtos, $item);
            }
        } else {
            $produtos = $entradaEstoque->estoqueEntradaProdutos()
                ->get()
                ->map(function ($item) {
                    $produto = Produto::find($item->id_produto);
                    return array_merge($item->only([
                        'quantidade',
                        'lote',
                        'valor',
                        'valor_custo',
                        'validade'
                    ]), [
                        'id' => $item->id_produto,
                        'descricao' => $produto->descricao,
                        'unidade' => $produto->unidade()->first()->descricao,
                        'id_entrada_produto' => $item->id
                    ]);
                })->toArray();
        }

        return view('instituicao.estoque_entrada/editar', \compact('estoqueEntradaProdutos', 'produtos', 'tiposDocumentos', 'entradaEstoque', 'estoques', 'fornecedores'));
    }

    public function update(CriarEstoqueEntradaRequest $request, EstoqueEntradas $entradaEstoque)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_estoque_entrada');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($entradaEstoque->instituicao_id === $instituicao->id, 403);

        DB::transaction(function () use ($request, $instituicao, $entradaEstoque) {
            $dados = collect($request->validated());
            $entradaEstoque->update($dados->except('produtos')->toArray());

            $usuario_logado = $request->user('instituicao');

            $entradaEstoque->criarLogEdicao(
                $usuario_logado,
                $instituicao->id
            );

            $index_produtos = [];
            $produtos = collect($dados->get('produtos', []))
                ->filter(function ($produtos) use ($instituicao) {
                    $entrada = EstoqueEntradaProdutos::where('id', $produtos['id_entrada_produto'] ?? null)->whereHas('entrada', function(Builder $query) use ($instituicao) {
                        $query->where('instituicao_id', $instituicao->id);
                    })->first();
                    $diff = $produtos['quantidade'] - $entrada->quantidade;

                    return !is_null($produtos['id']) && (empty($produtos['id_entrada_produto']) || (!empty($entrada) && ($entrada->quantidade_estoque + $diff > 0)));
                })
                ->map(function ($produtos, $key) use (&$index_produtos) {
                    if(!empty($produtos['id_entrada_produto'])) {
                        $index_produtos[$produtos['id_entrada_produto']] = $key;
                    }

                    return [
                        'id_entrada_produto' => $produtos['id_entrada_produto'] ?? null,
                        'id_produto' => $produtos['id'],
                        'quantidade' => $produtos['quantidade'],
                        'lote' => $produtos['lote'],
                        'valor' => floatvalue($produtos['valor']),
                        'valor_custo' => floatvalue($produtos['valor_custo']),
                        'validade' => $produtos['validade']
                    ];
                });

            // Identifica os produtos a serem deletados
            $produtos_a_deletar = $entradaEstoque->estoqueEntradaProdutos()->get()->filter(function($item) use ($index_produtos) {
                return ($index_produtos[$item->id] ?? null) === null;
            });
            foreach($produtos_a_deletar as $produto) {
                $produto->delete();
            }

            foreach($produtos as $produto) {
                if(($produto['id_entrada_produto'] ?? null) !== null) {
                    $produto_entrada = EstoqueEntradaProdutos::find($produto['id_entrada_produto']);
                    $produto_entrada->update($produto);
                    $produto_entrada->criarLogEdicao(
                        $usuario_logado,
                        $instituicao->id
                    );
                } else {
                    $produto_entrada = $entradaEstoque->estoqueEntradaProdutos()->create($produto);
                    $produto_entrada->criarLogCadastro(
                        $usuario_logado,
                        $instituicao->id
                    );
                }
            }

            return $entradaEstoque;
        });

        return redirect()->route('instituicao.estoque_entrada.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Editado com sucesso!'
        ]);
    }


    public function destroy(Request $request, EstoqueEntradas $entradaEstoque)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_estoque_entrada');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));

        abort_unless($entradaEstoque->instituicao_id === $instituicao->id, 403);

        DB::transaction(function () use ($entradaEstoque, $request, $instituicao) {
            $entradaEstoque->delete();

            $usuario_logado = $request->user('instituicao');

            $entradaEstoque->criarLogExclusao(
                $usuario_logado,
                $instituicao->id
            );

            return $entradaEstoque;
        });

        return redirect()->route('instituicao.estoque_entrada.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Excluído com sucesso!'
        ]);
    }

    public function buscarEntradaProduto(BuscarEntradaProdutos $request)
    {
        $dados = collect($request->validated());
        $search = $dados->get('search', '');

        // Define as entradas dos produtos que serão retornados (usado para preencher formulário automaticamente)
        $entradas = $dados->get('entradas_produtos', []);
        if (empty($search) && empty($entradas)) {
            return response()->json([]);
        }

        // Define a baixa de estoque cujas alterações no estoque não serão consideradas
        $instituicao = $request->session()->get('instituicao');
        $baixa = EstoqueBaixa::where('instituicao_id', $instituicao)->find($request->get('baixa_estoque'));
        $entradas = EstoqueEntradaProdutos::buscarProdutosEmEstoque($search, $baixa, $entradas)->simplePaginate(15);

        return response()->json([
            'next' => $entradas->nextPageUrl() ? true : false,
            'items' => $entradas->items(),
            'ammount' => $entradas->count()
        ]);
    }

    public function buscarLoteProduto(BuscarLoteRequest $request)
    {
        $instituicao = $request->session()->get('instituicao');
        $dados = collect($request->validated());
        $busca = $dados->get('search', '');
        
        // Filtrar por busca
        if ($dados->get('strict', 0) != 1) {
            $results = EstoqueEntradaProdutos::where('lote', 'like', "%$busca%");
        } else {
            $results = EstoqueEntradaProdutos::where('lote', $busca);
        }
        
        // Remover da contagem os itens adicionados anteriormente pela entrada
        if(!empty($dados->get('id_entrada'))) {
            $entrada = EstoqueEntradas::where('instituicao_id', $instituicao)->where('id', $dados->get('id_entrada'))->first();
            $results->where('id_entrada', '!=', $entrada->id);
        }

        $results = $results->simplePaginate(15);
        return response()->json([
            'next' => $results->nextPageUrl() ? true : false,
            'items' => $results->items(),
            'ammount' => $results->count()
        ]);
    }
}
