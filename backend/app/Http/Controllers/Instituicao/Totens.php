<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Totens\InstituicaoCreateTotem;
use Illuminate\Support\Facades\DB;
use App\Totem;
use App\Instituicao;
use App\FilaTotem;

class Totens extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_totens');
        return view('instituicao.totens/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_totens');
        $filas_disponiveis = Instituicao::findOrFail($request->session()->get('instituicao'))->filasTriagem()->get();
        return view('instituicao.totens/criar', \compact('filas_disponiveis'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InstituicaoCreateTotem $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_totens');

        DB::transaction(function () use ($request) {
            $usuario = $request->user('instituicao');
            $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
            // Coleciona os dados validados para inserir no totem todos exceto filas
            $dados = collect($request->validated());
            $totem = $instituicao->totens()->create($dados->except('filas')->toArray());
            // Pega as filas e insere no totem
            $totem->overwrite($totem->filasTotem(), $dados->get('filas', []));
            $totem->criarLogCadastro($usuario);
        });

        return redirect()->route('instituicao.triagem.totens.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Totem cadastrado com sucesso!'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Totem $totem)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_totens');
        $filas_selecionadas = $totem->filasTriagem()->get();
        $filas_disponiveis = Instituicao::findOrFail($request->session()->get('instituicao'))->filasTriagem()->whereNotIn('id', $filas_selecionadas)->get();
        return view('instituicao.totens/editar', \compact('totem', 'filas_disponiveis', 'filas_selecionadas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InstituicaoCreateTotem $request, Totem $totem)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_totens');

        DB::transaction(function () use ($request, $totem) {
            $usuario = $request->user('instituicao');
            $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
            // Impedir edição entre instituições
            abort_unless($instituicao->id == $totem->instituicoes_id, '403');
            // Coleciona os dados validados para inserir no totem todos exceto filas
            $dados = collect($request->validated());
            $totem->update($dados->except('filas')->toArray());
            // Pega as filas e insere no totem
            $totem->overwrite($totem->filasTotem(), $dados->get('filas', []));
            $totem->criarLogEdicao($usuario);
        });

        return redirect()->route('instituicao.triagem.totens.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Totem atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Totem $totem)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_totens');

        DB::transaction(function () use ($totem, $request) {
            $usuario = $request->user('instituicao');
            $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
            // Impedir edição entre instituições
            abort_unless($instituicao->id == $totem->instituicoes_id, '403');
            $totem->delete();
            $totem->criarLogExclusao($usuario);
        });

        return redirect()->route('instituicao.triagem.totens.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Totem excluído com sucesso!'
        ]);
    }
}
