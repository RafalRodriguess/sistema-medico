<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Instituicao;
use App\MotivoCancelamentoExame;
use App\Http\Requests\MotivoCancelamentoExame\{
    InstituicaoCreateMotivoCancelamentoExame,
    InstituicaoEditMotivoCancelamentoExame
};
use App\InstituicaoProcedimentos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MotivosCancelamentoExame extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_motivos_cancelamento_exame');

        return view('instituicao.motivos_cancelamento_exame/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_cancelamento_exame');

        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        return view('instituicao.motivos_cancelamento_exame/criar', [
            'tipos' => MotivoCancelamentoExame::tipos,
            'procedimentos_instituicoes' => $instituicao->procedimentosInstituicoes()->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InstituicaoCreateMotivoCancelamentoExame $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_cancelamento_exame');

        DB::transaction(function () use ($request) {
            $dados = $request->validated();
            $instituicao = $request->session()->get('instituicao');
            // Verificando se o procedimento existe e está cadastrado na instituição do usuario logado
            $procedimento = InstituicaoProcedimentos::findOrFail($dados['procedimento_instituicao_id']);

            abort_unless($instituicao == $procedimento->instituicao->id, 403);
            $motivo = MotivoCancelamentoExame::create([
                'descricao' => $dados['descricao'],
                'tipo' => $dados['tipo'],
                'ativo' => $dados['ativo'],
                'procedimento_instituicao_id' => $dados['procedimento_instituicao_id']
            ]);
            $motivo->criarLogCadastro($request->user('instituicao'));
        });

        return redirect()->route('instituicao.motivoscancelamentoexame.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de cancelamento criado com sucesso!'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, MotivoCancelamentoExame $motivo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_motivos_cancelamento_exame');
        // Verificando se o usuario pertence a instituição do registro
        $usuario = $request->user('instituicao');
        abort_unless(!empty($usuario->instituicao()->find($motivo->instituicao->id)), 403);
        return view('instituicao.motivos_cancelamento_exame/editar', [
            'tipos' => MotivoCancelamentoExame::tipos,
            'motivo' => $motivo
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\InstituicaoEditMotivoCancelamentoExame  $request
     * @param  MotivoCancelamentoExame  $motivo
     * @return \Illuminate\Http\Response
     */
    public function update(InstituicaoEditMotivoCancelamentoExame $request, MotivoCancelamentoExame $motivo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_cancelamento_exame');

        DB::transaction(function () use ($request, $motivo) {
            $dados = $request->validated();
            // Verificando se o procedimento existe e está cadastrado na instituição do usuario logado
            $usuario = $request->user('instituicao');
            abort_unless(!empty($usuario->instituicao()->find($motivo->instituicao->id)), 403);
            $motivo->fill($dados);
            $motivo->save();
            $motivo->criarLogEdicao($usuario);
        });

        return redirect()->route('instituicao.motivoscancelamentoexame.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de cancelamento atualizado com sucesso!'
        ]);
    }

    /**
     * Switch the resource between active and inactive
     *
     * @param Request $request
     * @param int $motivo
     * @return \Illuminate\Http\Response
     */
    public function switch(Request $request, MotivoCancelamentoExame $motivo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_cancelamento_exame');

        DB::transaction(function () use ($request, $motivo) {
            $usuario = $request->user('instituicao');
            // Verificando se o usuario pertence a instituição do registro
            $usuario = $request->user('instituicao');
            abort_unless($usuario->instituicao->where('id', '=', $motivo->instituicao->id)->isNotEmpty(), 403);
            $motivo->ativo = !$motivo->ativo;
            $motivo->update();
            $motivo->criarLogEdicao($usuario);
        });

        return redirect()->back()->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de cancelamento atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, MotivoCancelamentoExame $motivo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_modalidades_exame');

        DB::transaction(function () use ($motivo, $request) {
            // Verificando se o usuario pertence a instituição do registro
            $usuario = $request->user('instituicao');
            abort_unless($usuario->instituicao()->where('id', '=', $motivo->instituicao->id)->count(), 403);
            $motivo->delete();
            $motivo->criarLogExclusao($usuario);
            return $motivo;
        });

        return redirect()->route('instituicao.motivoscancelamentoexame.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de cancelamento excluído com sucesso!'
        ]);
    }
}
