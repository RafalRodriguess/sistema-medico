<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ModalidadesExame\{
    InstituicaoCreateModalidadeExame,
    InstituicaoEditModalidadeExame
};
use Illuminate\Support\Facades\DB;
use App\ModalidadeExame;

class ModalidadesExame extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_modalidades_exame');

        return view('instituicao.modalidades_exame/lista');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_modalidades_exame');

        return view('instituicao.modalidades_exame/criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InstituicaoCreateModalidadeExame $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_modalidades_exame');

        DB::transaction(function () use ($request) {
            $usuario = $request->user('instituicao');
            $dados = $request->validated();
            $modalidade = ModalidadeExame::create([
                'sigla' => $dados['sigla'],
                'descricao' => $dados['descricao'],
                'instituicao_id' => $request->session()->get('instituicao'),
            ]);
            $modalidade->criarLogCadastro($usuario);
        });

        return redirect()->route('instituicao.modalidades.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modalidade criada com sucesso!'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, ModalidadeExame $modalidade)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_modalidades_exame');
        // Verificando se o usuario pertence a instituição do registro
        $usuario = $request->user('instituicao');
        abort_unless($usuario->instituicao()->where('id', '=', $modalidade->instituicao->id)->count(), 403);
        return view('instituicao.modalidades_exame/editar', \compact('modalidade'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InstituicaoEditModalidadeExame $request, ModalidadeExame $modalidade)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_modalidades_exame');

        DB::transaction(function () use ($request, $modalidade) {
            // Verificando se o usuario pertence a instituição do registro
            $usuario = $request->user('instituicao');
            abort_unless($usuario->instituicao()->where('id', '=', $modalidade->instituicao->id)->count(), 403);
            $dados = $request->validated();
            $modalidade->fill($dados);
            $modalidade->update();
            $modalidade->criarLogEdicao($usuario);
            return $modalidade;
        });

        return redirect()->route('instituicao.modalidades.edit', [$modalidade])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modalidade atualizada com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ModalidadeExame $modalidade)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_modalidades_exame');

        DB::transaction(function () use ($modalidade, $request) {
            // Verificando se o usuario pertence a instituição do registro
            $usuario = $request->user('instituicao');
            abort_unless($usuario->instituicao()->where('id', '=', $modalidade->instituicao->id)->count(), 403);
            $modalidade->delete();
            $modalidade->criarLogExclusao($usuario);
            return $modalidade;
        });

        return redirect()->route('instituicao.modalidades.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Modalidade excluída com sucesso!'
        ]);
    }
}
