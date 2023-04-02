<?php

namespace App\Http\Controllers\Instituicao;

use App\EstoqueInventario as AppEstoqueInventario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EstoqueInventario\CriarEstoqueInventarioRequest;
use App\Http\Requests\EstoqueInventario\EditarEstoqueInventarioRequest;
use App\Instituicao;
use App\Produto;
use Illuminate\Support\Facades\DB;

class EstoqueInventario extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort(403);
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_estoque_inventario');

        return view('instituicao.estoque_inventario/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_estoque_inventario');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));

        $estoqueInventario = $instituicao->estoqueInventario();
        $estoques =  $instituicao->estoques()->get();
        $produtos = $instituicao->produtos()->get();

        return view('instituicao.estoque_inventario/criar', \compact(
            'estoqueInventario',
            'estoques',
            'produtos'
        ));
    }

    public function store(CriarEstoqueInventarioRequest $request, EstoqueInventario $estoqueInventario)
    {
        config(["auth.defaults.guard" => "instituicao"]);

        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_estoque_inventario');

        DB::transaction(function () use ($request, $estoqueInventario) {
            $dados = collect($request->validated());
            $instituicao = Instituicao::find($request->session()->get("instituicao"));
            $usuario_logado = $request->user('instituicao');
            $estoqueInventario = $instituicao->estoqueInventario()->create(
                $dados->merge([
                    'usuario_id' => ($dados->get('aberta', 1) == 0) ? $usuario_logado->id : null
                ])
                    ->except('produtos')
                    ->toArray()
            );

            $produtos = collect($dados->get('produtos', []))
                ->filter(function ($produtos) {
                    return !is_null($produtos['id']) && !is_null('lote');
                })
                ->map(function ($produtos) {
                    return [
                        'produto_id' => $produtos['id'],
                        'quantidade' => $produtos['quantidade'],
                        'lote' => $produtos['lote'],
                        'quantidade_inventario' => $produtos['quantidade_inventario']
                    ];
                });

            $estoqueInventario->criarLogCadastro(
                $usuario_logado,
                $instituicao->id
            );

            foreach ($produtos as $produto) {

                $produto['estoque_inventario_id'] = $estoqueInventario->id;
                $EstoreEntradaProdutos  = $estoqueInventario->estoqueInventarioProdutos()->create($produto);

                $EstoreEntradaProdutos->criarLogCadastro(
                    $usuario_logado,
                    $instituicao->id
                );
            }
            return $estoqueInventario;
        });

        return redirect()->route('instituicao.estoque_inventario.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Inserido com sucesso!'
        ]);
    }

    public function edit(Request $request, AppEstoqueInventario $estoqueInventario)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_estoque_inventario');

        $usuario_logado = $request->user('instituicao');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless(
            $estoqueInventario->instituicao_id === $instituicao->id &&
                ($estoqueInventario->aberta == 1 || $estoqueInventario->usuario_id == $usuario_logado->id),
            403
        );

        $estoques =  $instituicao->estoques()->get();
        $produtos_selecionados = $estoqueInventario->estoqueInventarioProdutos()->get();

        return view('instituicao.estoque_inventario/editar', [
            'estoques' => $estoques,
            'inventario' => $estoqueInventario,
            'produtos_selecionados' => $produtos_selecionados
        ]);
    }

    public function update(CriarEstoqueInventarioRequest $request, AppEstoqueInventario $estoqueInventario)
    {
        config(["auth.defaults.guard" => "instituicao"]);
        $this->authorize('habilidade_instituicao_sessao', 'editar_estoque_inventario');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $usuario_logado = $request->user('instituicao');
        abort_unless(
            $estoqueInventario->instituicao_id === $instituicao->id &&
                ($estoqueInventario->aberta == 1 || $estoqueInventario->usuario_id == $usuario_logado->id),
            403
        );


        DB::transaction(function () use ($request, $estoqueInventario, $instituicao, $usuario_logado) {
            $dados = collect($request->validated());
            $estoqueInventario->update(
                $dados->merge([
                    'usuario_id' => ($dados->get('aberta', 1) == 0) ? $usuario_logado->id : null
                ])
                    ->except('produtos')
                    ->toArray()
            );

            $produtos = collect($dados->get('produtos', []))
                ->filter(function ($produtos) {
                    return !is_null($produtos['id']) && !is_null('lote');
                })
                ->map(function ($produtos) {
                    return [
                        'produto_id' => $produtos['id'],
                        'quantidade' => $produtos['quantidade'],
                        'lote' => $produtos['lote'],
                        'quantidade_inventario' => $produtos['quantidade_inventario']
                    ];
                });

            $estoqueInventario->criarLogCadastro(
                $usuario_logado,
                $instituicao->id
            );

            $estoqueInventario->overwrite($estoqueInventario->estoqueInventarioProdutos(), $produtos->toArray());
        });

        return redirect()->route('instituicao.estoque_inventario.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Editado com sucesso!'
        ]);
    }

    public function destroy(Request $request, AppEstoqueInventario $estoqueInventario)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_estoque_entrada');


        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $usuario_logado = $request->user('instituicao');
        abort_unless(
            $estoqueInventario->instituicao_id === $instituicao->id &&
                ($estoqueInventario->aberta == 1 || $estoqueInventario->usuario_id == $usuario_logado->id),
            403
        );

        DB::transaction(function () use ($estoqueInventario, $request, $instituicao, $usuario_logado) {
            $estoqueInventario->delete();

            $estoqueInventario->criarLogExclusao(
                $usuario_logado,
                $instituicao->id
            );

            return $estoqueInventario;
        });

        return redirect()->route('instituicao.estoque_inventario.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Excluído com sucesso!'
        ]);
    }
}
