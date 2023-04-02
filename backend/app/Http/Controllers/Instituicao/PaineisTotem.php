<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaineisTotem\CreatePaineisTotemRequest;
use App\Instituicao;
use App\PainelTotem;
use App\TipoChamadaTotem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaineisTotem extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_paineis_totem');

        return view('instituicao.paineis-totem/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_paineis_totem');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $origens = $instituicao->origens()->get();
        $opcoes = TipoChamadaTotem::getOpcoes($instituicao)->get();

        return view('instituicao.paineis-totem/criar', \compact('origens', 'opcoes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePaineisTotemRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_paineis_totem');

        DB::transaction(function () use ($request) {
            $dados = collect($request->validated());
            $instituicao = Instituicao::find($request->session()->get("instituicao"));
            $usuario_logado = $request->user('instituicao');
            $painel = $instituicao->paineisTotem()->create($dados->except('opcoes')->toArray());
            $opcoes = collect($dados->get('opcoes', []))->whereNotNull('titulo')->whereNotNull('local')->toArray();
            $painel->overwrite($painel->painelHasTipo(), $opcoes);
            $painel->criarLogInstituicaoCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.totens.paineis.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Painel cadastrado com sucesso!'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, PainelTotem $painel)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_paineis_totem');

        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        $origens = $instituicao->origens()->get();
        $opcoes = TipoChamadaTotem::getOpcoes($instituicao, $painel)->get();

        return view('instituicao.paineis-totem/editar', \compact('painel', 'origens', 'opcoes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreatePaineisTotemRequest $request, PainelTotem $painel)
    {
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($painel->instituicoes_id == $instituicao->id && $this->authorize('habilidade_instituicao_sessao', 'editar_paineis_totem'), 403);
        
        DB::transaction(function () use ($request, $painel, $instituicao) {
            $dados = collect($request->validated());
            $usuario_logado = $request->user('instituicao');
            $painel->update($dados->except('opcoes')->toArray());
            $opcoes = collect($dados->get('opcoes', []))->whereNotNull('titulo')->whereNotNull('local')->toArray();
            $painel->overwrite($painel->painelHasTipo(), $opcoes);
            $painel->criarLogInstituicaoEdicao($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.totens.paineis.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Painel atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, PainelTotem $painel)
    {
        $instituicao = Instituicao::find($request->session()->get("instituicao"));
        abort_unless($painel->instituicoes_id == $instituicao->id && $this->authorize('habilidade_instituicao_sessao', 'excluir_paineis_totem'), 403);

        DB::transaction(function () use ($request, $painel, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $painel->delete();
            $painel->criarLogInstituicaoExclusao($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.totens.paineis.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Painel excluído com sucesso!'
        ]);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, PainelTotem $painel)
    {
        $instituicao = Instituicao::find($request->session()->get("instituicao"));

        abort_unless($painel->instituicoes_id == $instituicao->id && $this->authorize('habilidade_instituicao_sessao', 'visualizar_paineis_totem'), 403);

        return view('instituicao.paineis-totem/show', \compact('painel'));
    }
}
