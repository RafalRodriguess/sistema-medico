<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
use App\Instituicao; 
use App\Comprador; 
use App\Http\Requests\comprador\CriarCompradorRequest; 
use Illuminate\Support\Facades\DB;

class Compradores extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_comprador'); 
        return view('instituicao.compradores.lista');
     
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_comprador');   
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $usuarios = $instituicao->instituicaoUsuarios()->get();   
        return view('instituicao.compradores.criar', \compact('usuarios'));
    }

 
    public function store(CriarCompradorRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_comprador');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated(); 

            $dados['ativo'] = $request->boolean('ativo');

            $comprador = $instituicao->compradores()->create($dados);
            $comprador->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.compradores.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Compradores criado com sucesso!'
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
    public function edit(Request $request, Comprador $comprador)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_comprador'); 

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $usuarios = $instituicao->instituicaoUsuarios()->get(); 

        return view('instituicao.compradores.editar',\compact("comprador","usuarios"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarCompradorRequest $request, Comprador $comprador)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_comprador');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$comprador->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $comprador){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();

            $dados['ativo'] = $request->boolean('ativo'); 

            $comprador->update($dados);
            $comprador->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.compradores.index', [$comprador])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Comprador alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Comprador $comprador)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_estoques');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$comprador->instituicao_id, 403);
        DB::transaction(function () use ($comprador, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $comprador->delete();
            $comprador->criarLogExclusao($usuario_logado, $instituicao); 

            return $comprador;
        });
        
        return redirect()->route('instituicao.compradores.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Compradores excluído com sucesso!'
        ]);
    }
}
