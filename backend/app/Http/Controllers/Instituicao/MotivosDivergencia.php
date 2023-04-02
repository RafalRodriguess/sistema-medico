<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\MotivosDivergenciaRequest;
use App\Instituicao;
use App\MotivoDivergencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MotivosDivergencia extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_motivos_divergencia');

        return view('instituicao.motivos-divergencia/lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_divergencia');

        return view('instituicao.motivos-divergencia/criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MotivosDivergenciaRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_divergencia');

        $dados = $request->validated();
        DB::transaction(function () use ($request, $dados) {
            $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
            $motivo = $instituicao->motivosDivergencia()->create($dados);
            $motivo->criarLogInstituicaoCadastro($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.motivos-divergencia.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de divergência cadastrado com sucesso!'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(MotivoDivergencia $motivo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_motivos_divergencia');

        return view('instituicao.motivos-divergencia/editar', \compact('motivo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MotivosDivergenciaRequest $request, MotivoDivergencia $motivo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_divergencia');

        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
        abort_unless($instituicao->id === $motivo->instituicoes_id, 403);

        DB::transaction(function () use ($motivo, $instituicao, $request) {
            $dados = $request->validated();
            $motivo->fill($dados);
            $motivo->save();
            $motivo->criarLogInstituicaoEdicao($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.motivos-divergencia.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de divergência atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, MotivoDivergencia $motivo)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_motivos_divergencia');

        $instituicao = Instituicao::findOrFail($request->session()->get('instituicao'));
        abort_unless($instituicao->id === $motivo->instituicoes_id, 403);

        DB::transaction(function () use ($motivo, $instituicao, $request) {
            $motivo->delete();
            $motivo->criarLogInstituicaoExclusao($request->user('instituicao'), $instituicao->id);
        });

        return redirect()->route('instituicao.motivos-divergencia.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Motivo de divergência excluído com sucesso!'
        ]);
    }
}
