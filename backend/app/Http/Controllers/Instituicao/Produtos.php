<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\Produtos\BuscarProdutosRequest;
use App\Instituicao;
use Illuminate\Http\Request;

use App\Http\Requests\Produtos\CriarProdutoRequest;
use App\Produto;
use Illuminate\Support\Facades\DB;

class Produtos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_produtos');
        return view('instituicao.produtos.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_produtos');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $especies = $instituicao->especies()->get();
        $classes = $instituicao->classes()->get();
        $unidades = $instituicao->unidades()->get();

        return view('instituicao.produtos.criar', \compact('especies','classes','unidades'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarProdutoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_produtos');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            $dados['kit'] = $request->boolean('kit');
            $dados['mestre'] = $request->boolean('mestre');
            $dados['generico'] = $request->boolean('generico');
            $dados['opme'] = $request->boolean('opme');

            $produto = $instituicao->produtos()->create($dados);
            $produto->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.produtos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Produto criado com sucesso!'
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
    public function edit(Request $request, Produto $produto)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_estoques');

        $instituicao = $request->session()->get('instituicao');
        abort_unless($instituicao===$produto->instituicao_id, 403);
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $especies = $instituicao->especies()->get();
        $classes = $instituicao->classes()->get();
        $unidades = $instituicao->unidades()->get();

        return view('instituicao.produtos.editar',\compact("produto","especies","classes","unidades"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarProdutoRequest $request, Produto $produto)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_produtos');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$produto->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $produto){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            $dados['kit'] = $request->boolean('kit');
            $dados['mestre'] = $request->boolean('mestre');
            $dados['generico'] = $request->boolean('generico');
            $dados['opme'] = $request->boolean('opme');

            $produto->update($dados);
            $produto->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.produtos.index', [$produto])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Produto alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request, Produto $produto)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_produtos');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$produto->instituicao_id, 403);
        DB::transaction(function () use ($produto, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $produto->delete();
            $produto->criarLogExclusao($usuario_logado, $instituicao);

            return $produto;
        });

        return redirect()->route('instituicao.produtos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Produtos excluído com sucesso!'
        ]);
    }

    /**
     * Busca de produtos global, recebe uma requisição post e
     * retorna os resultandos em forma de JSON, limite de resultados
     * retornados é de até 50 e pode ser informado na request, suporta
     * paginação caso seja especificado na request
     */
    public function getProdutos(BuscarProdutosRequest $request)
    {
        $dados = collect($request->validated());
        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $query = $instituicao->produtos()
            ->with([
                'classe',
                'unidade',
                'especie'
            ])
            ->orderBy('produtos.descricao', 'asc');

        if($dados->get('ids', false)) {
            $query->whereIn('produtos.id', $dados->get('ids'));
        } else if($dados->get('id', false)) {
            $query->where('produtos.id', $dados->get('id'));
        } else {
            $query->where('produtos.descricao', 'like', "%{$request->get('search')}%");
        }

        if($dados->get('paginate', false)) {
            $results = $query->simplePaginate(30);
            return response()->json([
                "results" => $results->items(),
                "pagination" => array(
                    "more" => !empty($results->nextPageUrl()),
                )
            ]);
        } else {
            return response()->json($query->limit(50)->get());
        }
    }
}
