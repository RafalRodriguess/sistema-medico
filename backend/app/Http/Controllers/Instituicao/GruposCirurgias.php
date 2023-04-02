<?php

namespace App\Http\Controllers\Instituicao;

use App\GrupoCirurgia;
use App\Http\Controllers\Controller;
use App\Http\Requests\GruposCirurgias\CriarGruposCirurgiasRequest;
use App\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GruposCirurgias extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_grupos_cirurgias');
        return view('instituicao.grupos_cirurgias.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_grupos_cirurgias');
        return view('instituicao.grupos_cirurgias.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarGruposCirurgiasRequest $request, GrupoCirurgia $grupoCirurgia)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_grupos_cirurgias');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $grupoCirurgia = $instituicao->gruposCirurgias()->create($dados);
            $grupoCirurgia->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.gruposCirurgias.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Grupo de Cirurgia criada com sucesso!'
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
    public function edit(GrupoCirurgia $grupoCirurgia)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_grupos_cirurgias');
        return view('instituicao.grupos_cirurgias.editar', \compact('grupoCirurgia'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarGruposCirurgiasRequest $request, GrupoCirurgia $grupoCirurgia)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_grupos_cirurgias');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$grupoCirurgia->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $grupoCirurgia){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $grupoCirurgia->update($dados);
            $grupoCirurgia->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.gruposCirurgias.edit', [$grupoCirurgia])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Grupo de Cirugia alterada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, GrupoCirurgia $grupoCirurgia)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_grupos_cirurgias');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$grupoCirurgia->instituicao_id, 403);
        DB::transaction(function () use ($grupoCirurgia, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $grupoCirurgia->delete();
            $grupoCirurgia->criarLogExclusao($usuario_logado, $instituicao);
           
            return $grupoCirurgia;
        });
        
        return redirect()->route('instituicao.gruposCirurgias.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Grupo de Cirurgia excluída com sucesso!'
        ]);
    }
}
