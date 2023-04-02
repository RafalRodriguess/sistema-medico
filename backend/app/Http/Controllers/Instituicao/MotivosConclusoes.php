<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\MotivoConclusao\CriarMotivoConclusaoRequest;
use App\Instituicao;
use App\MotivoConclusao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MotivosConclusoes extends Controller
{
    /**
     * Mostra a tabela de Motivos de Altas
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_motivos_conclusoes');

        return view('instituicao.motivos-conclusoes.lista');
    }

    /**
     * Mostra o formulário para registro de Motivos de Altas
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_conclusoes');

        return view('instituicao.motivos-conclusoes.criar');
    }

    /**
     * Cadastra um novo registro de Motivos de Altas
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarMotivoConclusaoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_conclusoes');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();

        DB::transaction(function() use ($dados, $instituicao, $request){
            $motivo = $instituicao->motivosConclusao()->create($dados);
            $motivo->criarLogCadastro($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.motivoConclusao.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de Conclusão registrado com sucesso!'
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
    public function edit(Request $request, MotivoConclusao $motivo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_motivos_conclusoes');
        $instituicao = $request->session()->get('instituicao');
        abort_unless($instituicao === $motivo->instituicao_id, 403);
        
        return view('instituicao.motivos-conclusoes.editar', \compact('motivo'));
    }

    /**
     * Atualiza um determinado registro de Motivos de Altas
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarMotivoConclusaoRequest $request, MotivoConclusao $motivo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_motivos_conclusoes');

        $instituicao = $request->session()->get('instituicao');

        abort_unless($instituicao === $motivo->instituicao_id, 403);

        $dados = $request->validated();

        $motivo = DB::transaction(function() use ($dados, $instituicao, $request, $motivo){
            $motivo->update($dados);
            $motivo->criarLogEdicao($request->user('instituicao'), $instituicao);
            return $motivo;
        });

        return redirect()->route('instituicao.motivoConclusao.edit', [$motivo])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de Conclusão editado com sucesso!'
        ]);
    }

    /**
     * Deleta um determinado registro de Motivos de Altas
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, MotivoConclusao $motivo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_motivos_conclusoes');

        $instituicao = $request->session()->get('instituicao');

        abort_unless($instituicao === $motivo->instituicao_id, 403);

        DB::transaction(function() use ($instituicao, $request, $motivo){
            $motivo->delete();
            $motivo->criarLogExclusao($request->user('instituicao'), $instituicao);
        });

        return redirect()->route('instituicao.motivoConclusao.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de Conclusão excluído com sucesso!'
        ]);
    }
}
