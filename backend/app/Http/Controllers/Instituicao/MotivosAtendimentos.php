<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\MotivoAtendimento\CriarMotivoAtendimentoRequest;
use App\Instituicao;
use App\MotivoAtendimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MotivosAtendimentos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_motivos_atendimento');

        return view('instituicao.motivos_atendimento.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_atendimento');

        return view('instituicao.motivos_atendimento.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarMotivoAtendimentoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_atendimento');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();

        DB::transaction(function() use ($dados, $request, $instituicao){
            $usuario_logado = $request->user('instituicao');
            $motivo_atendimento = $instituicao->motivoAtendimento()->create($dados);
            $motivo_atendimento->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.motivos_atendimento.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo atendimento cadastrado com sucesso!'
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
    public function edit(MotivoAtendimento $motivo_atendimento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_motivos_atendimento');

        return view('instituicao.motivos_atendimento.editar', \compact('motivo_atendimento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarMotivoAtendimentoRequest $request, MotivoAtendimento $motivo_atendimento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_motivos_atendimento');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $motivo_atendimento->instituicao_id, 403);

        $dados = $request->validated();

        $motivo_atendimento = DB::transaction(function() use ($dados, $request, $instituicao, $motivo_atendimento){
            $motivo_atendimento->update($dados);
            $motivo_atendimento->criarLogEdicao($request->user('instituicao'), $instituicao->id);
            return $motivo_atendimento;
        });

        return redirect()->route('instituicao.motivos_atendimento.edit', [$motivo_atendimento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo atendimento editado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, MotivoAtendimento $motivo_atendimento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_motivos_atendimento');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $motivo_atendimento->instituicao_id, 403);

        DB::transaction(function() use ($request, $instituicao, $motivo_atendimento){
            $motivo_atendimento->delete();
            $motivo_atendimento->criarLogExclusao($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.motivos_atendimento.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo atendimento excluído com sucesso!'
        ]);
    }
}
