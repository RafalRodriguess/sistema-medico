<?php

namespace App\Http\Controllers\Instituicao;

use App\AtividadeMedica;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instituicao\AtividadeMedicaRequest;
use App\Instituicao;
use Illuminate\Http\Request;

class AtividadesMedicas extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_atividades_medicas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $atividades = $instituicao->atividadesMedicas()->orderBy('ordem_apresentacao')->paginate(10);

        return view('instituicao.atividades_medicas.index', \compact('atividades'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_atividades_medicas');

        $tipos = AtividadeMedica::getTipoFuncaoOptions();

        $atividades_medica = (object) [
            'descricao' => '',
            'ordem_apresentacao' => '',
            'tipo_funcao' => '',
        ];

        return view('instituicao.atividades_medicas.create', \compact('tipos', 'atividades_medica'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Instituicao\AtividadeMedicaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AtividadeMedicaRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_atividades_medicas');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $atividade = AtividadeMedica::create([
            'instituicao_id' => $instituicao->id,
            'descricao' => $request->descricao,
            'tipo_funcao' => $request->tipo_funcao,
            'ordem_apresentacao' => $request->ordem_apresentacao,
        ]);

        $atividade->criarLogCadastro($request->user('instituicao'), $instituicao->id);

        return redirect()->route('instituicao.atividadesMedicas.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Atividade médica cadastrada com sucesso!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Http\Requests\Instituicao\AtividadeMedicaRequest  $atividades_medica
     * @return \Illuminate\Http\Response
     */
    public function show(AtividadeMedica $atividades_medica)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\AtividadeMedica  $atividades_medica
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, AtividadeMedica $atividades_medica)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_atividades_medicas');

        $tipos = AtividadeMedica::getTipoFuncaoOptions();

        return view('instituicao.atividades_medicas.edit', \compact('tipos', 'atividades_medica'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Instituicao\AtividadeMedicaRequest  $request
     * @param  \App\AtividadeMedica  $atividades_medica
     * @return \Illuminate\Http\Response
     */
    public function update(AtividadeMedicaRequest $request, AtividadeMedica $atividades_medica)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_atividades_medicas');

        $dados = $request->validated();
        $atividades_medica->update($dados);

        $atividades_medica->criarLogEdicao($request->user('instituicao'), $atividades_medica->instituicao_id);

        return redirect()->route('instituicao.atividadesMedicas.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Atividade médica atualizada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AtividadeMedica  $atividades_medica
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, AtividadeMedica $atividades_medica)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_atividades_medicas');

        $atividades_medica->delete();
        $atividades_medica->criarLogExclusao($request->user('instituicao'), $atividades_medica->instituicao_id);

        return redirect()->route('instituicao.atividadesMedicas.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Atividade médica excluida com sucesso!'
        ]);
    }
}
