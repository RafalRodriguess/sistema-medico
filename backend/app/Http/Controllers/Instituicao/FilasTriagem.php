<?php

namespace App\Http\Controllers\Instituicao;

use App\FilaTriagem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Instituicao;
use App\Http\Requests\FilasTriagem\{
    CreateFilasTriagem,
    EditFilasTriagem
};

class FilasTriagem extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_filas_triagem');
        return view('instituicao.filas-triagem/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_filas_triagem');
        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
        $identificadores = FilaTriagem::getAvailableIdenficadores($instituicao->id);
        $origens = $instituicao->origens()->get();
        $processos_disponiveis = $instituicao->processosTriagem()->get();
        return view('instituicao.filas-triagem/criar', \compact('identificadores', 'origens', 'processos_disponiveis'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateFilasTriagem $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_filas_triagem');

        DB::transaction(function () use ($request) {
            $usuario = $request->user('instituicao');
            $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
            $fila = $instituicao->filasTriagem()->create(collect($request->validated())->except('processos')->toArray());
            // Sobrescrever os processos existentes
            $fila->overwrite($fila->processosFilaTriagem(), $request->get('processos', []));
            $fila->criarLogCadastro($usuario);
        });

        return redirect()->route('instituicao.triagem.filas.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Fila cadastrada com sucesso!'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, FilaTriagem $fila)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_filas_triagem');
        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
        $identificadores = FilaTriagem::getAvailableIdenficadores($instituicao->id, $fila->identificador);
        $origens = $instituicao->origens()->get();
        $processos_selecionados = $fila->processosCompletos()->get();
        // Todos os processos exceto os já escolhidos em fila
        $processos_disponiveis = $instituicao->processosTriagem()->WhereNotIn('id', $processos_selecionados->pluck('processos_triagem_id'))->get();
        return view('instituicao.filas-triagem/editar', \compact('fila', 'identificadores', 'origens', 'processos_disponiveis', 'processos_selecionados'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditFilasTriagem $request, FilaTriagem $fila)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_filas_triagem');

        DB::transaction(function () use ($request, $fila) {
            $usuario = $request->user('instituicao');
            $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
            // Impedir edição entre instituições
            abort_unless($instituicao->id == $fila->instituicoes_id, '403');
            $fila->update(collect($request->validated())->except('processos')->toArray());
            // Sobrescrever os processos existentes
            $fila->overwrite($fila->processosFilaTriagem(), $request->get('processos', []));
            $fila->criarLogEdicao($usuario);
        });

        return redirect()->route('instituicao.triagem.filas.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Fila atualizada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, FilaTriagem $fila)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_filas_triagem');

        DB::transaction(function () use ($fila, $request) {
            $usuario = $request->user('instituicao');
            $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
            // Impedir edição entre instituições
            abort_unless($instituicao->id == $fila->instituicoes_id, '403');
            $fila->delete();
            $fila->criarLogExclusao($usuario);
        });

        return redirect()->route('instituicao.triagem.filas.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Fila excluída com sucesso!'
        ]);
    }
}
