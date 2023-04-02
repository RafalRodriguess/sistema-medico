<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApresentacaoConvenio;
use App\Http\Requests\ApresentacoesConvenio\CreateApresentacaoConvenio;
use App\Instituicao;
use Illuminate\Support\Facades\DB;

class ApresentacoesConvenio extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_apresentacoes_convenio');
        return view('instituicao.apresentacoes-convenio/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_apresentacoes_convenio');
        return view('instituicao.apresentacoes-convenio/criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateApresentacaoConvenio $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_apresentacoes_convenio');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        DB::transaction(function () use ($request, $instituicao) {
            $usuario = $request->user('instituicao');
            $apresentacao = $instituicao->apresentacaoConvenios()->create($request->validated());
            $apresentacao->criarLogCadastro($usuario, $instituicao->id);
        });

        return redirect()->route('instituicao.convenios.apresentacoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Apresentação de convenio cadastrada com sucesso!'
        ]);
    }

    public function show()
    {
        # code...
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, ApresentacaoConvenio $apresentacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_apresentacoes_convenio');
        $instituicao_id = $request->session()->get('instituicao');
        abort_unless($instituicao_id === $apresentacao->instituicao_id, 403);
        return view('instituicao.apresentacoes-convenio/editar', \compact('apresentacao'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateApresentacaoConvenio $request, ApresentacaoConvenio $apresentacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_apresentacoes_convenio');
        $instituicao_id = $request->session()->get('instituicao');
        abort_unless($instituicao_id === $apresentacao->instituicao_id, 403);

        DB::transaction(function () use ($request, $apresentacao, $instituicao_id) {
            $usuario = $request->user('instituicao');
            $apresentacao->update($request->validated());
            $apresentacao->criarLogEdicao($usuario, $instituicao_id);
        });

        return redirect()->route('instituicao.convenios.apresentacoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Apresentação de convenio atualizada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ApresentacaoConvenio $apresentacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_apresentacoes_convenio');
        $instituicao_id = $request->session()->get('instituicao');
        abort_unless($instituicao_id === $apresentacao->instituicao_id, 403);

        DB::transaction(function () use ($apresentacao, $request, $instituicao_id) {
            $usuario = $request->user('instituicao');
            $apresentacao->delete();
            $apresentacao->criarLogExclusao($usuario, $instituicao_id);
        });

        return redirect()->route('instituicao.convenios.apresentacoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Apresentação de convenio excluída com sucesso!'
        ]);
    }
}
