<?php

namespace App\Http\Controllers\Admin;

use App\GruposProcedimentos;
use App\Http\Requests\Grupos\CriarGrupoProcedimentoRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrupoProcedimentos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $this->authorize('habilidade_admin', 'visualizar_grupos');

        return view('admin.grupos_procedimentos/lista');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('habilidade_admin', 'cadastrar_grupos');
        return view('admin.grupos_procedimentos/criar');
    }


    public function edit(GruposProcedimentos $grupos_procedimento)
    {
        $grupo = $grupos_procedimento;
        $this->authorize('habilidade_admin', 'editar_grupos');
        return view('admin.grupos_procedimentos/editar', \compact('grupo'));
    }


    public function update(CriarGrupoProcedimentoRequest $request, GruposProcedimentos $grupos_procedimento)
    {
        $this->authorize('habilidade_admin', 'editar_grupos');
        $dados = $request->validated();

        DB::transaction(function () use ($request, $grupos_procedimento, $dados) {
            $grupos_procedimento->update($dados);

            $usuario_logado = $request->user('admin');
            $grupos_procedimento->criarLogEdicao($usuario_logado);

            return $grupos_procedimento;
        });

        //return redirect()->route('administradores.edit', [$usuario])->with('mensagem', 'Salvo com sucesso');
        return redirect()->route('grupos_procedimentos.edit', [$grupos_procedimento])->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Grupos atualizado com sucesso!'
        ]);
    }



    public function store(CriarGrupoProcedimentoRequest $request)
    {


        $this->authorize('habilidade_admin', 'cadastrar_grupos');

        $dados = $request->validated();

        DB::transaction(function () use ($dados, $request) {
            $grupo = GruposProcedimentos::create($dados);
            $usuario_logado = $request->user('admin');
            $grupo->criarLogCadastro($usuario_logado);
            return $grupo;
        });


        return redirect()->route('grupos_procedimentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Grupo criado com sucesso!'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Procedimento  $procedimento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, GruposProcedimentos $grupos_procedimento)
    {
        $this->authorize('habilidade_admin', 'excluir_grupos');
        DB::transaction(function () use ($request, $grupos_procedimento) {
            $grupos_procedimento->delete();

            $usuario_logado = $request->user('admin');
            $grupos_procedimento->criarLogExclusao($usuario_logado);

            return $grupos_procedimento;
        });

        return redirect()->route('grupos_procedimentos.index')->with('mensagem', [
            'icon' => 'success',
            'title' => 'Sucesso.',
            'text' => 'Procedimento excluído com sucesso!'
        ]);
    }
}
