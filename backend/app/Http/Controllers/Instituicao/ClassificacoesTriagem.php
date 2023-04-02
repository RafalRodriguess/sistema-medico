<?php

namespace App\Http\Controllers\Instituicao;

use App\ClassificacaoTriagem;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClassificacoesTriagem\CreateClassificacaoTriagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Instituicao;

class ClassificacoesTriagem extends Controller
{

    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_classificacoes_triagem');
        return view('instituicao.classificacoes-triagem/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     *
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_classificacoes_triagem');
        return view('instituicao.classificacoes-triagem/criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function store(CreateClassificacaoTriagem $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_classificacoes_triagem');

        DB::transaction(function () use ($request) {
            $usuario = $request->user('instituicao');
            $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
            $classificacao = $instituicao->classificacoesTriagem()->create($request->validated());
            $classificacao->criarLogCadastro($usuario);
        });

        return redirect()->route('instituicao.triagem.classificacoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Classificação cadastrada com sucesso!'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     */
    public function edit(Request $request, ClassificacaoTriagem $classificacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_classificacoes_triagem');
        abort_unless($request->session()->get('instituicao') == $classificacao->instituicao->id, '403');
        return view('instituicao.classificacoes-triagem/editar', \compact('classificacao'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     */
    public function update(CreateClassificacaoTriagem $request, ClassificacaoTriagem $classificacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_classificacoes_triagem');

        DB::transaction(function () use ($request, $classificacao) {
            $usuario = $request->user('instituicao');
            $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
            // Impedir edição entre instituições
            abort_unless($instituicao->id == $classificacao->instituicoes_id, '403');
            $classificacao->update($request->validated());
            $classificacao->criarLogEdicao($usuario);
        });

        return redirect()->route('instituicao.triagem.classificacoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Classificação atualizada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     */
    public function destroy(Request $request, ClassificacaoTriagem $classificacao)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_classificacoes_triagem');

        DB::transaction(function () use ($classificacao, $request) {
            $usuario = $request->user('instituicao');
            $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
            // Impedir edição entre instituições
            abort_unless($instituicao->id == $classificacao->instituicoes_id, '403');
            $classificacao->delete();
            $classificacao->criarLogExclusao($usuario);
        });

        return redirect()->route('instituicao.triagem.classificacoes.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Classificação excluída com sucesso!'
        ]);
    }
}
