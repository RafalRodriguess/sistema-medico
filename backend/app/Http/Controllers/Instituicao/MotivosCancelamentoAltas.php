<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\MotivoCancelamentoAlta;
use Illuminate\Http\Request;
use App\Http\Requests\MotivoCancelamentoAlta\{
    InstituicaoCreateMotivoCancelamentoAlta
};
use App\Instituicao;
use Illuminate\Support\Facades\DB;

class MotivosCancelamentoAltas extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_motivos_cancelamento_altas');

        return view('instituicao.internacao.motivos-cancelamento-altas.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_cancelamento_altas');

        return view('instituicao.internacao.motivos-cancelamento-altas.criar', [
            'tipos' => MotivoCancelamentoAlta::getTipos(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InstituicaoCreateMotivoCancelamentoAlta $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_cancelamento_altas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();

        DB::transaction(function() use ($dados, $request, $instituicao){
            $dados['ativo'] = (isset($dados['ativo']))? true: false;
            $motivo_cancelamento_alta = $instituicao->motivosCancelamentoAltas()->create($dados);
            $motivo_cancelamento_alta->criarLogCadastro($request->user('instituicao'));
        });

        return redirect()->route('instituicao.internacao.motivos-cancelamento-altas.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de Cancelamento de Alta registrado com sucesso!'
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
    public function edit(MotivoCancelamentoAlta $motivo_cancelamento_alta)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_motivos_cancelamento_altas');

        return view('instituicao.internacao.motivos-cancelamento-altas.editar', [
            'tipos' => MotivoCancelamentoAlta::getTipos(),
            'motivo_cancelamento_alta' => $motivo_cancelamento_alta
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InstituicaoCreateMotivoCancelamentoAlta $request, MotivoCancelamentoAlta $motivo_cancelamento_alta)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_motivos_cancelamento_altas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $motivo_cancelamento_alta->instituicao_id, 403);

        $dados = $request->validated();

        $motivo_cancelamento_alta = DB::transaction(function() use ($dados, $request, $instituicao, $motivo_cancelamento_alta){
            $dados['ativo'] = (isset($dados['ativo']))? true: false;
            $motivo_cancelamento_alta->update($dados);
            $motivo_cancelamento_alta->criarLogEdicao($request->user('instituicao'), $instituicao->id);
            return $motivo_cancelamento_alta;
        });

        return redirect()->route('instituicao.internacao.motivos-cancelamento-altas.edit', [$motivo_cancelamento_alta])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de Cancelamento de Alta editado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, MotivoCancelamentoAlta $motivo_cancelamento_alta)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_motivos_cancelamento_altas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $motivo_cancelamento_alta->instituicao_id, 403);

        DB::transaction(function() use ($request, $instituicao, $motivo_cancelamento_alta){
            $motivo_cancelamento_alta->delete();
            $motivo_cancelamento_alta->criarLogExclusao($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.internacao.motivos-cancelamento-altas.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de Cancelamento de Alta excluído com sucesso!'
        ]);
    }
}
