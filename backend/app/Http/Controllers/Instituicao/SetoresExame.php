<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\SetorExame;
use Illuminate\Http\Request;
use App\Http\Requests\SetoresExame\{
    InstituicaoCreateSetorExame,
    InstituicaoEditSetorExame
};
use App\Instituicao;
use Illuminate\Support\Facades\DB;

class SetoresExame extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_setores_exame');
        return view('instituicao.setores_exame/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_setores_exame');
        return view('instituicao.setores_exame/criar', ['todos_tipos' => SetorExame::tipos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InstituicaoCreateSetorExame $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_setores_exame');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));


        DB::transaction(function () use ($request, $instituicao) {
            $usuario = $request->user('instituicao');
            $dados = $request->validated();

            $modalidade = $instituicao->setoresExame()->create($dados);
            $modalidade->criarLogCadastro($usuario);
        });

        return redirect()->route('instituicao.setores.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Setor criado com sucesso!'
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
    public function edit(Request $request, SetorExame $setor)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_setores_exame');
        // Verificando se o usuario pertence a instituição do registro
        $usuario = $request->user('instituicao');
        abort_unless($usuario->instituicao->where('id', '=', $setor->instituicao->id)->count(), 403);
        return view('instituicao.setores_exame/editar', [
            'setor' => $setor,
            'todos_tipos' => SetorExame::tipos
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InstituicaoEditSetorExame $request, SetorExame $setor)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_setores_exame');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($setor->instituicao_id === $instituicao->id, 403);

        DB::transaction(function () use ($request, $setor) {
            $usuario = $request->user('instituicao');
            // Verificando se o usuario pertence a instituição do registro
            $usuario = $request->user('instituicao');
            $dados = $request->validated();
            $setor->fill($dados);
            $setor->update();
            $setor->criarLogEdicao($usuario);
            return $setor;
        });

        return redirect()->route('instituicao.setores.edit', [$setor])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Setor atualizado com sucesso!'
        ]);
    }

    /**
     * Switch the resource between active and inactive
     *
     * @param Request $request
     * @param SetorExame $setor
     * @return \Illuminate\Http\Response
     */
    public function switch(Request $request, SetorExame $setor)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_setores_exame');

        DB::transaction(function () use ($request, $setor) {
            $usuario = $request->user('instituicao');
            // Verificando se o usuario pertence a instituição do registro
            $usuario = $request->user('instituicao');
            abort_unless($usuario->instituicao->where('id', '=', $setor->instituicao->id)->count(), 403);
            $setor->ativo = !$setor->ativo;
            $setor->update();
            $setor->criarLogEdicao($usuario);
            return $setor;
        });

        return redirect()->back()->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Setor atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, SetorExame $setor)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_setores_exame');

        DB::transaction(function () use ($setor, $request) {
            $usuario = $request->user('instituicao');
            // Verificando se o usuario pertence a instituição do registro
            $usuario = $request->user('instituicao');
            abort_unless($usuario->instituicao->where('id', '=', $setor->instituicao->id)->count(), 403);
            $setor->delete();
            $usuario = $request->user('instituicao');
            $setor->criarLogExclusao($usuario);

            return $setor;
        });

        return redirect()->route('instituicao.setores.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Setor excluído com sucesso!'
        ]);
    }
}
