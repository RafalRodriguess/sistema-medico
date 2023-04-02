<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MotivoAlta;
use App\Http\Requests\MotivosAltas\{
    InstituicaoCreateMotivoAlta,
};
use App\Instituicao;
use Illuminate\Support\Facades\DB;

class MotivosAltas extends Controller
{
    /**
     * Mostra a tabela de Motivos de Altas
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_motivos_altas');

        return view('instituicao.internacao.motivos-altas.lista');
    }

    /**
     * Mostra o formulário para registro de Motivos de Altas
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_altas');

        return view('instituicao.internacao.motivos-altas.criar', [
            'tipos' => MotivoAlta::getTipos(),
            'motivos_transferencia' => MotivoAlta::getMotivosTransferencia()
        ]);
    }

    /**
     * Cadastra um novo registro de Motivos de Altas
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InstituicaoCreateMotivoAlta $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_altas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();

        DB::transaction(function() use ($dados, $instituicao, $request){
            $motivo_alta = $instituicao->motivosAltas()->create($dados);
            $motivo_alta->criarLogCadastro($request->user('instituicao'));
        });

        return redirect()->route('instituicao.internacao.motivos-altas.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de Alta registrado com sucesso!'
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
     * Mostra o formulário de edição para um registro de Motivos de Altas
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, MotivoAlta $motivo_alta)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_motivos_altas');
        
        return view('instituicao.internacao.motivos-altas.editar', [
            'motivo_alta' => $motivo_alta,
            'tipos' => MotivoAlta::getTipos(),
            'motivos_transferencia' => MotivoAlta::getMotivosTransferencia()
        ]);
    }

    /**
     * Atualiza um determinado registro de Motivos de Altas
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InstituicaoCreateMotivoAlta $request, MotivoAlta $motivo_alta)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_motivos_altas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $motivo_alta->instituicao_id, 403);

        $dados = $request->validated();

        $motivo_alta = DB::transaction(function() use ($dados, $instituicao, $request, $motivo_alta){
            if(!isset($dados['motivo_transferencia_id'])) $dados['motivo_transferencia_id'] = null;
            $motivo_alta->update($dados);
            $motivo_alta->criarLogEdicao($request->user('instituicao'), $instituicao->id);
            return $motivo_alta;
        });

        return redirect()->route('instituicao.internacao.motivos-altas.edit', [$motivo_alta])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de Alta editado com sucesso!'
        ]);
    }

    /**
     * Deleta um determinado registro de Motivos de Altas
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, MotivoAlta $motivo_alta)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_motivos_altas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $motivo_alta->instituicao_id, 403);

        DB::transaction(function() use ($instituicao, $request, $motivo_alta){
            $motivo_alta->delete();
            $motivo_alta->criarLogExclusao($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.internacao.motivos-altas.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de Alta excluído com sucesso!'
        ]);
    }
}
