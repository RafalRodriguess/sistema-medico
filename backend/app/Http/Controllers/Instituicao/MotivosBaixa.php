<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Instituicao;
use App\MotivoBaixa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MotivosBaixa extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_motivos_baixa');

        return view('instituicao.motivos-baixa.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_baixa');

        return view('instituicao.motivos-baixa.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_baixa');

        DB::transaction(function() use ($request) {
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $usuario_logado = $request->user('instituicao');
            $motivo = $instituicao->motivoBaixa()->create($request->validate([
                'descricao' => ['required', 'string', 'min:1']
            ]));

            $motivo->criarLogCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.motivos-baixa.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de baixa registrado com sucesso!'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, MotivoBaixa $motivo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_motivos_baixa');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($motivo->instituicao_id == $instituicao->id, 403);

        return view('instituicao.motivos-baixa.editar', [
            'motivo' => $motivo
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MotivoBaixa $motivo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_motivos_baixa');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($motivo->instituicao_id == $instituicao->id, 403);

        DB::transaction(function() use ($motivo, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $motivo->update($request->validate([
                'descricao' => ['required', 'string', 'min:1']
            ]));

            $motivo->criarLogEdicao($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.motivos-baixa.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de baixa atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, MotivoBaixa $motivo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_motivos_baixa');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($motivo->instituicao_id == $instituicao->id, 403);

        DB::transaction(function() use ($motivo, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');
            $motivo->criarLogExclusao($usuario_logado, $instituicao->id);
            $motivo->delete();
        });

        return redirect()->route('instituicao.motivos-baixa.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de baixa deletado com sucesso!'
        ]);
    }
}
