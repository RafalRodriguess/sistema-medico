<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\PacotesProcedimentos\CreatePacoteProcedimentoRequest;
use App\Instituicao;
use App\PacotePrcedimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PacotesProcedimentos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_pacotes');
        return view('instituicao.pacotes_procedimentos.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_pacotes');
        return view('instituicao.pacotes_procedimentos.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePacoteProcedimentoRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_pacotes');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));

        $dados = $request->validated();

        DB::transaction(function () use ($dados, $request, $instituicao) {
            $pacote = $instituicao->pacoteProcedimentos()->create($dados);
            $usuario_logado = $request->user('instituicao');
            $pacote->criarLogCadastro($usuario_logado);
            return $pacote;
        });


        return redirect()->route('instituicao.pacotesProcedimentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Pacote criado com sucesso!'
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
    public function edit(PacotePrcedimento $pacote_procedimento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_pacotes');
        
        return view('instituicao.pacotes_procedimentos/editar', \compact('pacote_procedimento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreatePacoteProcedimentoRequest $request, PacotePrcedimento $pacote_procedimento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_pacotes');
        $dados = $request->validated();
        DB::transaction(function () use ($request, $pacote_procedimento, $dados) {
            $pacote_procedimento->update($dados);

            $usuario_logado = $request->user('instituicao');
            $pacote_procedimento->criarLogEdicao($usuario_logado);

            return $pacote_procedimento;
        });

        return redirect()->route('instituicao.pacotesProcedimentos.edit', [$pacote_procedimento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Pacote alterado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, PacotePrcedimento $pacote_procedimento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_pacotes');
        DB::transaction(function () use ($request, $pacote_procedimento) {
            $pacote_procedimento->procedimentoVinculo()->detach();
            $pacote_procedimento->delete();

            $usuario_logado = $request->user('instituicao');
            $pacote_procedimento->criarLogExclusao($usuario_logado);

            return $pacote_procedimento;
        });

        return redirect()->route('instituicao.pacotesProcedimentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Pacote procedimento excluído com sucesso!'
        ]);
    }

    public function verVinculo(Request $request, PacotePrcedimento $pacote_procedimento)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_pacotes');
        $instituicao = Instituicao::find($request->session()->get("instituicao"));

        $procedimentos = $pacote_procedimento->procedimentoVinculo()->get();

        return view('instituicao.pacotes_procedimentos/vincular', \compact('pacote_procedimento', 'procedimentos'));
    }

    public function salvarVinculo(Request $request, PacotePrcedimento $pacote_procedimento){
        
        $dados = collect($request->input('procedimento_id'))
        ->filter(function ($procedimento){
            return $procedimento;
        })
        ->map(function ($procedimento) use ($pacote_procedimento){
            return [
                "pacote_id" => $pacote_procedimento->id,
                "procedimento_id" => $procedimento,
            ];
        });

        DB::transaction(function () use ($request, $pacote_procedimento, $dados) {
            
            $pacote_procedimento->procedimentoVinculo()->detach();
            $pacote_procedimento->procedimentoVinculo()->attach($dados);

            $usuario_logado = $request->user('instituicao');

            return $pacote_procedimento;
        });

        $procedimentos = $pacote_procedimento->procedimentoVinculo()->get();

        return view('instituicao.pacotes_procedimentos/vincular', \compact('pacote_procedimento', 'procedimentos'))->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Procedimentos vinculados ao pacote com sucesso!'
        ]);
    }
}
