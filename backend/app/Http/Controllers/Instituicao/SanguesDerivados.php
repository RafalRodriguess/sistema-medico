<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\SangueDerivado\CriarSangueDerivadoRequest;
use App\Instituicao;
use App\SangueDerivado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SanguesDerivados extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_sangues_derivados');

        return view('instituicao.sangues_derivados.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_sangues_derivados');

        return view('instituicao.sangues_derivados.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriarSangueDerivadoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_sangues_derivados');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        $dados = $request->validated();

        DB::transaction(function() use ($dados, $instituicao, $request){
            $sangue_derivado = $instituicao->sanguesDerivados()->create($dados);
            $sangue_derivado->criarLogCadastro($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.sanguesDerivados.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Sangue e derivados criado com sucesso!'
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
    public function edit(Request $request, SangueDerivado $sangue_derivado)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_sangues_derivados');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $sangue_derivado->instituicao_id, 403);

        return view('instituicao.sangues_derivados.editar', \compact('sangue_derivado'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriarSangueDerivadoRequest $request, SangueDerivado $sangue_derivado)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_sangues_derivados');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $sangue_derivado->instituicao_id, 403);

        $dados = $request->validated();

        $sangue_derivado = DB::transaction(function() use ($dados, $instituicao, $request, $sangue_derivado){
            $sangue_derivado->update($dados);
            $sangue_derivado->criarLogEdicao($request->user('instituicao'), $instituicao->id);
            return $sangue_derivado;
        });

        return redirect()->route('instituicao.sanguesDerivados.edit', [$sangue_derivado])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Sangue e derivados atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, SangueDerivado $sangue_derivado)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_sangues_derivados');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));

        abort_unless($instituicao->id === $sangue_derivado->instituicao_id, 403);

        DB::transaction(function () use ($sangue_derivado, $request, $instituicao) {
            $sangue_derivado->delete();
            $sangue_derivado->criarLogExclusao($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.sanguesDerivados.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Sangue e derivados excluído com sucesso!'
        ]);
    }
}
