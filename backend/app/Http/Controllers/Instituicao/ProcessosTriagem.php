<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessosTriagem\CreateProcessoTriagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Instituicao;
use App\ProcessoTriagem;

class ProcessosTriagem extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_processos_triagem');
        return view('instituicao.processos-triagem/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_processos_triagem');
        return view('instituicao.processos-triagem/criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CreateProcessoTriagem  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProcessoTriagem $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_processos_triagem');

        DB::transaction(function () use ($request) {
            $usuario = $request->user('instituicao');
            $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
            $processo = $instituicao->processosTriagem()->create($request->validated());
            $processo->criarLogCadastro($usuario);
        });

        return redirect()->route('instituicao.triagem.processos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Processo cadastrado com sucesso!'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  ProcessoTriagem  $processo
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, ProcessoTriagem $processo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_processos_triagem');
        return view('instituicao.processos-triagem/editar', \compact('processo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateProcessoTriagem $request, ProcessoTriagem $processo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_processos_triagem');

        DB::transaction(function () use ($request, $processo) {
            $usuario = $request->user('instituicao');
            $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
            // Impedir edição entre instituições
            abort_unless($instituicao->id == $processo->instituicoes_id, '403');
            $processo->update($request->validated());
            $processo->criarLogEdicao($usuario);
        });

        return redirect()->route('instituicao.triagem.processos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Processo atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ProcessoTriagem $processo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_processos_triagem');

        DB::transaction(function () use ($processo, $request) {
            $usuario = $request->user('instituicao');
            $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
            // Impedir edição entre instituições
            abort_unless($instituicao->id == $processo->instituicoes_id, '403');
            $processo->delete();
            $processo->criarLogExclusao($usuario);
        });

        return redirect()->route('instituicao.triagem.processos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Processo excluído com sucesso!'
        ]);
    }
}
