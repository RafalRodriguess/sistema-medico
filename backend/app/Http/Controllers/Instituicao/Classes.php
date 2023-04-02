<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Instituicao; 
use App\Classe; 
use App\Http\Requests\classe\CriarClasseRequest; 
use Illuminate\Support\Facades\DB;

class Classes extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_classes'); 
        return view('instituicao.classes.lista');
     
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_classes');  
        //ERRADO
        // return view('instituicao.classes.criar', \compact('centro_custos'));
        return view('instituicao.classes.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarClasseRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_classes');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $classe = $instituicao->classes()->create($dados);
            $classe->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.classes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Classe criado com sucesso!'
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
    public function edit( Classe $classe)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_classes');  
        return view('instituicao.classes.editar',\compact("classe"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarClasseRequest $request, Classe $classe)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_classes');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$classe->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $classe){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $classe->update($dados);
            $classe->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.classes.index', [$classe])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Classe alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Classe $classe)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_classes');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$classe->instituicao_id, 403);
        DB::transaction(function () use ($classe, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $classe->delete();
            $classe->criarLogExclusao($usuario_logado, $instituicao); 

            return $classe;
        });
        
        return redirect()->route('instituicao.classes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Classe excluído com sucesso!'
        ]);
    }
}
