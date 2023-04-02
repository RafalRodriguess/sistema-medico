<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
 use App\Http\Requests\EstoqueEntradaProdutos\EditarEstoqueEntradaProdutosRequest;
 use App\Http\Requests\EstoqueEntradaProdutos\CriarEstoqueEntradaProdutosRequest;
use App\EstoqueEntradaProdutos;
use Illuminate\Support\Facades\DB;

class EstoqueEntradaProduto extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_admin', 'visualizar_estoque_entrada_produtos');
        return view('admin.estoque_entrada_produtos/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($request)
    {

        $this->authorize('habilidade_admin', 'cadastrar_estoque_entrada_produtos');

        $estoqueEntradaProduto = EstoqueEntradaProdutos::where('id_entrada',$request)->get();
        $produtos =  DB::table('produtos')->get();

        if(!$estoqueEntradaProduto->isEmpty()){
            return view('admin.estoque_entrada_produtos/criar',compact('estoqueEntradaProduto','produtos'));
        }else{
            $estoqueEntradaProduto = $request;
            return view('admin.estoque_entrada_produtos/criar',compact('estoqueEntradaProduto','produtos'));
        }
    }

    public function store(CriarEstoqueEntradaProdutosRequest $request)
    {

        $this->authorize('habilidade_admin', 'cadastrar_estoque_entrada_produtos');
        $dados = $request->validated();

        $dados['id_entrada'] = $request->id_entrada;
        $dados['id_produto'] = $request->id_produto;
        $dados['quantidade'] = $request->quantidade;
        $dados['lote'] = $request->lote;

        $estoqueEntradasProdutos = EstoqueEntradaProdutos::create($dados);

        DB::transaction(function () use ($request,$estoqueEntradasProdutos, $dados){

            $usuario_logado = $request->user('admin');
            $estoqueEntradasProdutos->criarLogCadastro($usuario_logado);

            return $estoqueEntradasProdutos;
        });

        return redirect()->route('estoque_entrada_produtos.index',[$estoqueEntradasProdutos->id_entrada])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Inserido com sucesso!'
        ]);
    }


    public function edit(Request $estoqueEntradasProdutos)
    {
        $this->authorize('habilidade_admin', 'editar_estoque_entrada_produtos');

        $estoqueEntradasProdutos = EstoqueEntradaProdutos::where('id',$estoqueEntradasProdutos->id)->first();
        $produtos =  DB::table('produtos')->get();

        return view('admin.estoque_entrada_produtos/editar', \compact('estoqueEntradasProdutos','produtos'));

    }

    public function update(EditarEstoqueEntradaProdutosRequest $request)
    {
        $this->authorize('habilidade_admin', 'editar_estoque_entrada_produtos');

        $estoqueEntradasProdutos = EstoqueEntradaProdutos::find($request->id);

        $dados = $request->validated();
        $estoqueEntradasProdutos->id = $request['id'];
        $estoqueEntradasProdutos->id_entrada = $request['id_entrada'];
        $estoqueEntradasProdutos->id_produto = $request['id_produto'];
        $estoqueEntradasProdutos->quantidade = $request['quantidade'];
        $estoqueEntradasProdutos->lote = $request['lote'];

        DB::transaction(function () use ($estoqueEntradasProdutos,$dados){
            $estoqueEntradasProdutos->update($dados);

            return $estoqueEntradasProdutos;
        });

        return redirect()->route('estoque_entrada_produtos.index',[$estoqueEntradasProdutos->id_entrada])->with('mensagem', [
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
    public function destroy(Request $request)
    {
        $this->authorize('habilidade_admin', 'excluir_estoque_entrada_produtos');

        $estoqueEntradasProdutos = EstoqueEntradaProdutos::find($request->id);
        $this->authorize('habilidade_admin', 'excluir_comercial');
        DB::transaction(function () use ($estoqueEntradasProdutos,$request){
            $estoqueEntradasProdutos->delete();

            $usuario_logado = $request->user('admin');
            $estoqueEntradasProdutos->criarLogExclusao(
              $usuario_logado
            );

            return $estoqueEntradasProdutos;
        });

        return redirect()->route('estoque_entrada_produtos.index',[$estoqueEntradasProdutos->id_entrada])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Excluído com sucesso!'
        ]);
    }
}
