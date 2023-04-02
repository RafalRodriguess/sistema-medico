<?php

namespace App\Http\Controllers\Instituicao;

use App\Http\Controllers\Controller;
use App\Http\Requests\CadastroLocalEntregaExameRequest;
use App\Instituicao;
use App\LocalEntregaExame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocaisEntregaExames extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('habilidade_instituicao_sessao', 'visualizar_locais_entrega_exames');

        return view('instituicao.locais-entrega-exame.lista');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_locais_entrega_exames');

        return view('instituicao.locais-entrega-exame.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CadastroLocalEntregaExameRequest $request)
    {
        $this->authorize('habilidade_instituicao_sessao', 'cadastrar_locais_entrega_exames');
        DB::transaction(function() use ($request) {
            $instituicao = Instituicao::find($request->session()->get('instituicao'));
            $usuario_logado = $request->user('instituicao');

            $local = $instituicao->locaisEntregaExame()->create($request->validated());
            $local->criarLogInstituicaoCadastro($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.locais-entrega-exames.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Local cadastrado com sucesso!'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, LocalEntregaExame $local)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_locais_entrega_exames');

        return view('instituicao.locais-entrega-exame.editar', \compact('local'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CadastroLocalEntregaExameRequest $request, LocalEntregaExame $local)
    {
        $this->authorize('habilidade_instituicao_sessao', 'editar_locais_entrega_exames');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($local->instituicao_id == $instituicao->id, 403);

        DB::transaction(function() use ($local, $request, $instituicao) {
            $usuario_logado = $request->user('instituicao');

            $local->update($request->validated());
            $local->criarLogInstituicaoEdicao($usuario_logado, $instituicao->id);
        });

        return redirect()->route('instituicao.locais-entrega-exames.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Local atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, LocalEntregaExame $local)
    {
        $this->authorize('habilidade_instituicao_sessao', 'excluir_locais_entrega_exames');
        $instituicao = Instituicao::find($request->session()->get('instituicao'));
        abort_unless($local->instituicao_id == $instituicao->id, 403);

        DB::transaction(function() use ($request, $instituicao, $local) {
            $usuario_logado = $request->user('instituicao');

            $local->criarLogInstituicaoExclusao($usuario_logado, $instituicao->id);
            $local->delete();
        });

        return redirect()->route('instituicao.locais-entrega-exames.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Local excluído com sucesso!'
        ]);
    }
}
