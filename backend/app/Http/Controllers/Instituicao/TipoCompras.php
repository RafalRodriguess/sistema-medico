<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Instituicao; 
use App\TipoProduto; 
use App\Http\Requests\tipoCompras\CriarTipoComprasRequest;
use App\TipoCompra;
use Illuminate\Support\Facades\DB;

class TipoCompras extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_tipo_compras'); 
        return view('instituicao.tipo_compras.lista');
     
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_tipo_compras');  
        return view('instituicao.tipo_compras.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarTipoComprasRequest $request)
    {
        
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_tipo_compras');
        
       
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $dados['urgente'] = $request->boolean('urgente');

            $especie = $instituicao->tipoCompras()->create($dados);
            $especie->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.tipoCompras.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Tipo Compras criado com sucesso!'
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
    public function edit( TipoCompra $tipoCompra)
    { 
        $this->authorize('habilidade_instituicao_sessao', 'editar_tipo_compras');   
        return view('instituicao.tipo_compras.editar',\compact("tipoCompra"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarTipoComprasRequest $request, TipoCompra $tipoCompra)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_especies');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$tipoCompra->instituicao_id, 403);

        DB::transaction(function() use ($request, $instituicao, $tipoCompra){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $dados['urgente'] = $request->boolean('urgente');

            $tipoCompra->update($dados);
            $tipoCompra->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.tipoCompras.index', [$tipoCompra])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Tipo de Compras alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request, TipoCompra $tipoCompra)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_classes');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$tipoCompra->instituicao_id, 403);
        DB::transaction(function () use ($tipoCompra, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $tipoCompra->delete();
            $tipoCompra->criarLogExclusao($usuario_logado, $instituicao); 

            return $tipoCompra;
        });
        
        return redirect()->route('instituicao.tipoCompras.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Tipo de Compras excluído com sucesso!'
        ]);
    }
}
