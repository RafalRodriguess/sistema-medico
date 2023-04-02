<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\MotivosMortesRN\CriarMotivosMortesRNRequest;
use App\Instituicao;
use App\MotivoMorteRN;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MotivosMortesRN extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_motivos_mortes_rn');

        return view('instituicao.motivos_mortes_rn.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_mortes_rn');

        return view('instituicao.motivos_mortes_rn.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarMotivosMortesRNRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_mortes_rn');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        DB::transaction(function() use ($request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $motivoMorteRN = $instituicao->motivosMortesRN()->create($dados);
            $motivoMorteRN->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.motivosMortesRN.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de morte de recem nascido criado com sucesso!'
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
    public function edit(MotivoMorteRN $motivos_mortes_rn)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_motivos_mortes_rn');
        
        return view('instituicao.motivos_mortes_rn.editar',\compact("motivos_mortes_rn"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarMotivosMortesRNRequest $request, MotivoMorteRN $motivos_mortes_rn)

    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_motivos_mortes_rn');

        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$motivos_mortes_rn->instituicao_id, 403);
        DB::transaction(function() use ($request, $instituicao, $motivos_mortes_rn){
            $usuario_logado = $request->user('instituicao');
            $dados = $request->validated();
            
            $motivos_mortes_rn->update($dados);
            $motivos_mortes_rn->criarLogEdicao($usuario_logado, $instituicao);
        });

        return redirect()->route('instituicao.motivosMortesRN.edit', [$motivos_mortes_rn])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de morte de recem nascido alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, MotivoMorteRN $motivos_mortes_rn)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_motivos_mortes_rn');
        
        $instituicao = $request->session()->get("instituicao");
        abort_unless($instituicao===$motivos_mortes_rn->instituicao_id, 403);
        DB::transaction(function () use ($motivos_mortes_rn, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $motivos_mortes_rn->delete();
            $motivos_mortes_rn->criarLogExclusao($usuario_logado, $instituicao);
           
            return $motivos_mortes_rn;
        });
        
        return redirect()->route('instituicao.motivosMortesRN.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de morte de recem nascido excluído com sucesso!'
        ]);
    }
}
