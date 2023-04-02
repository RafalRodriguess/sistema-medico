<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
use App\Instituicao; 
use App\Especie; 
use App\Http\Requests\especie\CriarEspecieRequest; 
use Illuminate\Support\Facades\DB;

class Especies extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_especies'); 
        return view('instituicao.especies.lista');
     
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_especies');  
        return view('instituicao.especies.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarEspecieRequest $request)
    {
        
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_especies');
        
       
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $especie = $instituicao->especies()->create($dados);
            $especie->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.especies.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Especie criado com sucesso!'
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
    public function edit( Especie $especie)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_especies');  
        return view('instituicao.especies.editar',\compact("especie"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarEspecieRequest $request, Especie $especie)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_especies');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$especie->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $especie){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $especie->update($dados);
            $especie->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.especies.index', [$especie])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Especie alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Especie $especie)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_classes');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$especie->instituicao_id, 403);
        DB::transaction(function () use ($especie, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $especie->delete();
            $especie->criarLogExclusao($usuario_logado, $instituicao); 

            return $especie;
        });
        
        return redirect()->route('instituicao.especies.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Especie excluído com sucesso!'
        ]);
    }
}
