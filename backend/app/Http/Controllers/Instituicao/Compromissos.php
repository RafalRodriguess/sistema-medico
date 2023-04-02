<?php

namespace App\Http\Controllers\Instituicao;

use App\Compromisso;
use App\Http\Controllers\Controller;
use App\Http\Requests\Compromissos\CriarCompromissoRequest;
use App\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Compromissos extends Controller
{
    public function index()
    {
        
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_compromissos'); 
        return view('instituicao.compromissos.lista');
     
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_compromissos');   
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        return view('instituicao.compromissos.criar');
    }

 
    public function store(CriarCompromissoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_compromissos');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated(); 

            $compromisso = $instituicao->compromissos()->create($dados);
            $compromisso->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.compromissos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'compromissos criado com sucesso!'
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
    public function edit(Request $request, Compromisso $compromisso)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_compromissos'); 

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        $usuarios = $instituicao->instituicaoUsuarios()->get(); 

        return view('instituicao.compromissos.editar',\compact("compromisso"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarCompromissoRequest $request, Compromisso $compromisso)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_compromissos');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$compromisso->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $compromisso){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();

            $compromisso->update($dados);
            $compromisso->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.compromissos.index', [$compromisso])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Compromisso alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Compromisso $compromisso)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_estoques');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$compromisso->instituicao_id, 403);
        DB::transaction(function () use ($compromisso, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $compromisso->delete();
            $compromisso->criarLogExclusao($usuario_logado, $instituicao); 

            return $compromisso;
        });
        
        return redirect()->route('instituicao.compromissos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Compromisso excluído com sucesso!'
        ]);
    }
}
