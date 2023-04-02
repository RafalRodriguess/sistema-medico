<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\ViasAcesso\CriarViasAcessoRequest;
use App\Instituicao;
use App\ViaAcesso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViasAcesso extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_vias_acesso');
        return view('instituicao.vias_acesso.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_vias_acesso');
        return view('instituicao.vias_acesso.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarViasAcessoRequest $request, ViaAcesso $viaAcesso)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_vias_acesso');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $viaAcesso = $instituicao->viasAcesso()->create($dados);
            $viaAcesso->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.viasAcesso.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Via de acesso criada com sucesso!'
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
    public function edit(ViaAcesso $viaAcesso)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_vias_acesso');
        return view('instituicao.vias_acesso.editar', \compact('viaAcesso'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarViasAcessoRequest $request, ViaAcesso $viaAcesso)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_vias_acesso');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$viaAcesso->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $viaAcesso){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $viaAcesso->update($dados);
            $viaAcesso->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.viasAcesso.edit', [$viaAcesso])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Via de Acesso alterada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ViaAcesso $viaAcesso)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_vias_acesso');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$viaAcesso->instituicao_id, 403);
        DB::transaction(function () use ($viaAcesso, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $viaAcesso->delete();
            $viaAcesso->criarLogExclusao($usuario_logado, $instituicao);
           
            return $viaAcesso;
        });

        return redirect()->route('instituicao.viasAcesso.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Via de Acesso excluída com sucesso!'
        ]);
    }
}
